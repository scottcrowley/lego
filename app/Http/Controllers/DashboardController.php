<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
    }
}

// $client = new GuzzleHttp\Client(['headers' => ['Authorization' => 'key 48d2702df209d39241843b3b9ce2e865', 'Accept' => 'application/json']]);
// $client = new GuzzleHttp\Client();

// $r = $client->request('POST', 'https://rebrickable.com/api/v3/users/_token/', ['debug' => true, 'form_params' => ['key' => '48d2702df209d39241843b3b9ce2e865', 'username' => 'scott%40nihilonline.com', 'password' => '8&$1jLaL1PU1ZlTPmn']]);

// $r = $client->request('GET', 'https://rebrickable.com/api/v3/lego/colors/', ['debug' => true]);
