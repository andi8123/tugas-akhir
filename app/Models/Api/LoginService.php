<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginService
{
    protected static $baseUrl;

    public static function initialize()
    {
        self::$baseUrl = env('API_BASE_URL');
    }

    public static function boot()
    {
        self::initialize();
    }

    public static function authenticate($email, $password)
    {
        $response = Http::post(env('API_BASE_URL') . 'authentications?platform=web', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            // Store access token in session
            Session::put('access_token', $data['token']['access_token']);
            Session::put('roles', $data['roles']);

            return [
                'success' => true,
                'roles' => $data['roles'],
                'platform' => $data['platform'],
            ];
        }

        return [
            'success' => false,
            'message' => $response->json()['message'] ?? 'Login failed',
        ];
    }

    // Optional: Method to get token for reuse
    public static function getAccessToken()
    {
        return Session::get('access_token');
    }
}
