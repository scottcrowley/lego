<?php

namespace App;

use Zttp\Zttp;

class RebrickableApi
{
    protected $url = 'https://rebrickable.com/api/v3/';

    protected $urlParams = '';

    protected $credentials;

    protected $header = [];

    public $response;

    public function __construct()
    {
        $this->credentials = config('rebrickable.api');

        $this->verifyCredentials();
    }

    public function generateToken()
    {
        $this->appendUrl('users/_token/');

        if (($ok = $this->prepareExecution()) !== true) {
            return $ok;
        }

        $this->response = Zttp::withHeaders($this->header)->asFormParams()->post($this->url, [
            'username' => $this->credentials['email'],
            'password' => $this->credentials['password']
        ]);

        return $this->getResponse();
    }

    public function getColors(int $page = null, int $page_size = null, $ordering = null)
    {
        $this->appendUrl('lego/colors/');

        if (($ok = $this->prepareExecution()) !== true) {
            return $ok;
        }

        $params = [];

        if (! is_null($page)) {
            $this->appendUrlParam('page='.$page);
        }

        if (! is_null($page_size)) {
            $this->appendUrlParam('page_size='.$page_size);
        }
        if (! is_null($ordering)) {
            $this->appendUrlParam('ordering='.$ordering);
        }

        $this->response = Zttp::withHeaders($this->header)->get($this->url.$this->urlParams);

        return $this->getResponse();
    }

    protected function appendUrlParam($param)
    {
        if ($param == '') {
            return;
        }

        $urlParams = $this->urlParams;

        $this->urlParams = ($urlParams == '') ? '?'.$param : $urlParams.'&'.$param;
    }

    protected function appendUrl($path = '')
    {
        if ($path == '') {
            return;
        }
        $this->url = $this->url.$path;
    }

    // protected function generatePostParams($params = [], $includeCredentials = true)
    // {
    //     if (! is_array($params)) {
    //         $params = [];
    //     }

    //     if (! $includeCredentials) {
    //         return $params;
    //     }

    //     $params['key'] = $this->credentials->api_key;
    //     $params['username'] = $this->credentials->email;
    //     $params['password'] = $this->credentials->password;

    //     return $params;
    // }

    public function prepareExecution()
    {
        $this->header = ['Accept' => 'application/json', 'Authorization' => 'key '.$this->credentials['key']];

        return true;
    }

    // protected function execute($params = [], $method = 'post', $formParams = false)
    // {
    //     if (count($this->header)) {
    //         return $this->response = Zttp::withHeaders($this->header)->$method($this->url, $params);
    //     }
    //     return $this->response = Zttp::$method($this->url, $params);
    // }

    public function getResponse()
    {
        return (! is_null($this->response)) ? $this->response->json() : $this->response;
    }

    public function verifyCredentials()
    {
        abort_if(
            is_null($this->credentials)
            || (
                ! is_null($this->credentials) &&
                (
                    $this->credentials['email'] == '' ||
                    $this->credentials['password'] == '' ||
                    $this->credentials['key'] == ''
                )
            ),
            400,
            'Rebrickable Credentials are invalid or do not exist'
        );

        return true;
    }
}
