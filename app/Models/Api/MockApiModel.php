<?php

namespace App\Models\Api;

use App\Models\MockWebService;
use App\Models\MyWebService;

class MockApiModel
{
    protected static $endpoint;
    protected $service;

    public function __construct()
    {
        $this->service = new MockWebService(static::$endpoint);
    }

    protected static function handleResponse($response)
    {
        $data = $response->getData();
                
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $data->data ?? $data;
        }

        throw new \Exception($data->message ?? 'API request failed', $response->getStatusCode());
    }
}
