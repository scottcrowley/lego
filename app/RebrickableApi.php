<?php

namespace App;

use Zttp\Zttp;

class RebrickableApi
{
    protected $url = 'https://rebrickable.com/api/v3/';

    protected $credentials;

    protected $header = [];

    public $response;

    public function __construct(RebrickableCredentials $credentials)
    {
        $this->credentials = $credentials;

        $this->verifyCredentials();
    }

    public function generateToken()
    {
        $this->appendUrl('users/_token/');

        if (($ok = $this->prepareExecution()) !== true) {
            return $ok;
        }

        $this->response = Zttp::withHeaders($this->header)->asFormParams()->post($this->url, [
            'username' => $this->credentials->email,
            'password' => $this->credentials->password
        ]);

        return $this->getResponse();
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
        $this->header = ['Accept' => 'application/json', 'Authorization' => 'key '.$this->credentials->api_key];

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
                ($this->credentials->email == '' || $this->credentials->password == '')
            ),
            400,
            'Credentials are invalid or do not exist'
        );

        return true;
    }
}
