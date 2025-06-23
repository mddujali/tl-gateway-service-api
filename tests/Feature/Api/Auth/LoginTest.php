<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class LoginTest extends BaseTestCase
{
    public function test_it_should_return_unauthorized(): void
    {
        $path = 'tests/Fixtures/Api/Auth/unauthenticated.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/login' => Http::response(
                body: $body,
                status: Response::HTTP_UNAUTHORIZED
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.login'),
            data: [
                'email' => 'invalid.user@example.com',
                'password' => 'password',
            ],
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_login_a_user(): void
    {
        $data = [
            'email' => 'valid.user@example.com',
            'password' => 'password',
        ];

        $path = 'tests/Fixtures/Api/Auth/authenticated.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/login' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.login'),
            data: $data,
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJsonStructure([
            'message',
            'data' => [
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
            ],
        ]);
    }
}
