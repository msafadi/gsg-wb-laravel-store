<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpStack
{

    protected $key;

    protected $baseUrl = 'http://api.ipstack.com/';

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function get($ip)
    {
        // http://api.ipstack.com/217.147.0.89?access_key=da7c6da94a6f3c35575a2e1a2d008965
        $response = Http::baseUrl($this->baseUrl)
            ->get($ip, [
                'access_key' => $this->key,
            ]);

        return $response->json();
    }
}