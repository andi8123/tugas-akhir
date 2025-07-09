<?php

namespace App\Models\Api;

class Verifikasi extends MockApiModel
{
    protected static $endpoint = 'verifikasi';

    public static function getAll()
    {
        $instance = new static();
        $response = $instance->service->get();
        return self::handleResponse($response);
    }

    public static function getById($id)
    {
        $instance = new static();
        $response = $instance->service->get(null, "/$id");
        return self::handleResponse($response);
    }

    public static function create(array $data)
    {
        $instance = new static();
        $response = $instance->service->post($data);
        return self::handleResponse($response);
    }

    public static function update($id, array $data)
    {
        $instance = new static();
        $response = $instance->service->put($data, "/$id");
        return self::handleResponse($response);
    }

    public static function delete($id)
    {
        $instance = new static();
        $response = $instance->service->delete(null, "/$id");
        return self::handleResponse($response);
    }

    public static function verifikasi($id, array $data)
    {
        $instance = new static();
        $response = $instance->service->put($data, "/v2/verifikasi-pengajuan/$id");
        return self::handleResponse($response);
    }
}
