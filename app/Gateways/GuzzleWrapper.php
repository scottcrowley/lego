<?php

namespace App\Gateways;

use Zttp\Zttp;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

trait GuzzleWrapper
{
    /**
     * url to submit to
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
     * header information for the Guzzle request
     *
     * @var array
     */
    protected $header = [];

    /**
     * The entire response from the Guzzle request.
     *
     * @var \Zttp\ZttpResponse
     */
    public $response;

    /**
     * The result of $response->json()
     *
     * @var array
     */
    public $jsonResponse;

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
     * removes a given param from the urlParams string
     *
     * @param string $param
     * @return void
     */
    protected function removeUrlParam($param)
    {
        if ($param == '') {
            return;
        }

        $urlParams = collect(
            explode(
                '&',
                substr($this->urlParams, 1)
            )
        )
            ->filter(
                function ($value, $key) use ($param) {
                    return substr($value, 0, (strlen($param) + 1)) != $param.'=';
                }
            );

        if (! $urlParams->count()) {
            $this->urlParams = '';
        } else {
            $this->urlParams = '?'.implode('&', $urlParams->toArray());
        }
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

        if (substr($path, -1) != '/') {
            $path .= '/';
        }

        $this->url = $this->baseUrl.$path;
    }

    /**
     * Getter for full request url
     *
     * @return string
     */
    protected function getRequestUrl()
    {
        return $this->url.$this->urlParams;
    }

    /**
     * Resets the url & urlParams properties
     *
     * @return void
     */
    public function resetUrl()
    {
        $this->url = $this->urlParams = '';
    }

    /**
     * resets all the properties to their default
     *
     * @return void
     */
    public function resetRequest()
    {
        $this->resetUrl();
        $this->postParams = $this->header = $this->errors = [];
        $this->response = $this->results = null;
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

        $this->response = Zttp::withHeaders($this->header)->get($this->getRequestUrl());
    }

    /**
     * execute a Guzzle DELETE request
     *
     * @return void
     */
    protected function executeDelete()
    {
        $this->prepareExecution();

        $this->response = Zttp::withHeaders($this->header)->delete($this->url, $this->urlParams);
    }

    /**
     * Execute Pooled requests
     *
     * @param int $totalPages
     * @param string $url
     * @param array $firstPage
     * @return array
     */
    protected function executePool($totalPages, $url, $firstPage)
    {
        $client = $this->generateGuzzleClient();
        $requests = $this->generateGuzzleRequests($totalPages, $url);
        $all = $this->executeGuzzlePool($client, $requests, $firstPage);

        return $all;
    }

    /**
     * convert Guzzle response to json
     *
     * @return array
     */
    protected function parseResponse()
    {
        $this->jsonResponse = $this->response->json();
        return $this->jsonResponse;
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
     * gets the status code of the response
     *
     * @return void
     */
    public function getStatus()
    {
        return (is_null($this->response)) ? null : $this->response->status();
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
     * getter for if errors exist
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
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
     * @return \GuzzleHttp\Client
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
     * @return \GuzzleHttp\Psr7\Request
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
     * executes a generated Guzzle Request Pool
     *
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Request $requests
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
