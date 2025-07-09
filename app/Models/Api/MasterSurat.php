<?php

namespace App\Models\Api;

class MasterSurat extends BaseApiModel
{
    protected static $endpoint = 'surat/v2/master-surat';

    public static function getAll()
    {
        $instance = new static();
        $response = $instance->service->get();
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
}
