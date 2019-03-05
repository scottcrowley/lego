<?php

namespace App\Gateways;

use Zttp\Zttp;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

trait ApiCore
{
    /**
     * additional url path requirements based on the api action requested
     *
     * @var string
     */
    protected $url = '';

    /**
     * optional url parameters for the api action
     *
     * @var string
     */
    protected $urlParams = '';

    /**
     * optional post parameters for the api action
     *
     * @var array
     */
    protected $postParams = [];

    /**
     * array of credentials from the .env file
     *
     * @var array
     */
    protected $credentials;

    /**
     * header information for the Guzzle request
     *
     * @var array
     */
    protected $header = [];

    /**
     * The entire response from the Guzzle request. GuzzleHttp\Psr7\Response
     *
     * @var null
     */
    public $response;

    /**
     * array containing the specific results from the response object
     *
     * @var null
     */
    public $results;

    /**
     * array containing error details regarding the Zttp or Guzzle request
     *
     * @var array
     */
    public $errors = [];

    /**
     * constructor that sets & verifies the proper credentials
     *
     * @return void
     */
    public function __construct()
    {
        $this->credentials = config('rebrickable.api');

        $this->verifyCredentials();
    }

    /**
     * verifies the credentials from the .env file
     *
     * @return bool
     */
    public function verifyCredentials()
    {
        abort_if(
            is_null($this->credentials)
            || (
                ! is_null($this->credentials) &&
                (
                    $this->credentials['email'] == '' ||
                    $this->credentials['password'] == '' ||
                    $this->credentials['key'] == '' ||
                    $this->credentials['token'] == ''
                )
            ),
            400,
            'Rebrickable Credentials are invalid or do not exist'
        );

        return true;
    }

    /**
     * appends to given param to the urlParams string
     *
     * @param string $param
     * @return void
     */
    protected function appendUrlParam($param)
    {
        if ($param == '') {
            return;
        }

        $urlParams = $this->urlParams;

        $this->urlParams = ($urlParams == '') ? '?'.$param : $urlParams.'&'.$param;
    }

    /**
     * appends the given path to the url
     *
     * @param string $path
     * @return void
     */
    protected function appendUrl($path = '')
    {
        if ($path == '') {
            return;
        }

        if (! is_null($this->response)) {
            $this->resetUrl();
        }

        $this->url = $this->baseUrl.$path;
    }

    /**
     * Resets the url & urlParams properties
     *
     * @return void
     */
    protected function resetUrl()
    {
        $this->url = $this->urlParams = '';
    }

    /**
     * adds the given param to postParams array to be used in post requests
     *
     * @param string $param
     * @param string $value
     * @return bool
     */
    protected function addPostParam($param, $value)
    {
        if ($param == '' || $value == '') {
            return false;
        }

        if (! is_null($this->response)) {
            $this->postParams = [];
        }

        $this->postParams = array_merge($this->postParams, [$param => $value]);

        return true;
    }

    /**
     * constructs the header for the Guzzle request with credentials
     *
     * @return bool
     */
    protected function prepareExecution()
    {
        $this->header = ['Accept' => 'application/json', 'Authorization' => 'key '.$this->credentials['key']];

        return true;
    }

    /**
     * execute a Guzzle POST request
     *
     * @return void
     */
    protected function executePost()
    {
        $this->prepareExecution();

        $this->response = Zttp::withHeaders($this->header)->asFormParams()->post($this->url, $this->postParams);
    }

    /**
     * execute a Guzzle GET request
     *
     * @return void
     */
    protected function executeGet()
    {
        $this->prepareExecution();

        $this->response = Zttp::withHeaders($this->header)->get($this->url.$this->urlParams);
    }

    /**
     * convert Guzzle response to json
     *
     * @return void
     */
    protected function parseResponse()
    {
        return $this->response->json();
    }

    /**
     * parses the Guzzle response and either returns a collection or an array of the results
     *
     * @param bool $collection
     * @return mixed
     */
    public function getResults($collection = false)
    {
        if (! $this->validateResponse()) {
            return false;
        }

        $json = $this->parseResponse();

        $this->results = (isset($json['results'])) ? $json['results'] : $json;

        return ($collection) ? collect($this->results) : $this->results;
    }

    /**
     * validates the response
     *
     * @return bool
     */
    protected function validateResponse()
    {
        if (is_null($this->response)) {
            $this->generateErrors(0);
        } elseif ($this->response->isOK()) {
            return true;
        } else {
            $this->generateErrors($this->response->status());
        }
        return false;
    }

    /**
     * generates details based on a given status code
     *
     * @param int $status
     * @return void
     */
    protected function generateErrors($status)
    {
        $errors = [
            0 => 'No request has been made yet',
            400 => 'Something was wrong with the format of your request',
            401 => 'Unauthorized - your API key is invalid',
            403 => 'Forbidden - you do not have access to operate on the requested item(s)',
            404 => 'Item not found',
            429 => 'Request was throttled',
        ];

        $detail = (isset($errors[$status])) ? $errors[$status] : 'An unknown error occurred';

        $this->errors = [
            'status' => $status,
            'detail' => $detail
        ];
    }

    /**
     * getter for errors array
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * generates a new Guzzle Client
     *
     * @return Client
     */
    protected function generateGuzzleClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 120.0,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'key '.$this->credentials['key']
            ]
        ]);
    }

    /**
     * generates a Guzzle Request for each page
     *
     * @param int $totalPages
     * @param string $baseUrl
     * @return Request
     */
    protected function generateGuzzleRequests($totalPages, $baseUrl)
    {
        return function () use ($totalPages, $baseUrl) {
            for ($i = 2; $i <= $totalPages; $i++) {
                $uri = $baseUrl.$i;
                yield new Request('GET', $uri);
            }
        };
    }

    /**
     * executeGuzzlePool
     *
     * @param Client $client
     * @param Request $requests
     * @param array $data
     * @return array
     */
    protected function executeGuzzlePool($client, $requests, $data)
    {
        $pool = new Pool($client, $requests(), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) use (&$data) {
                $page = json_decode($response->getBody(), true);
                $data = array_merge($data, $page['results']);
            },
            'rejected' => function ($reason, $index) {
                abort(400, 'An error occurred while retrieving all of the requested data. Reason: '.$reason);
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return $data;
    }
}
