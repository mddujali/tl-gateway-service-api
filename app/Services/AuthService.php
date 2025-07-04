<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function login(array $credentials): Response
    {
        return Http::asJson()
            ->post($this->baseUrl() . '/login', $credentials);
    }

    public function refreshToken(array $data): Response
    {
        return Http::asJson()
            ->post($this->baseUrl() . '/refresh', $data);
    }

    public function logout(string $bearerToken): Response
    {
        return Http::asJson()
            ->withToken($bearerToken)
            ->post($this->baseUrl() . '/logout');
    }
    private function baseUrl(): string
    {
        return config('services.auth_service_api.url') . '/api/auth';
    }
}
