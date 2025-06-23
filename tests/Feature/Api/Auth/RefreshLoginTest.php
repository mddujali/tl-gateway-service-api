<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class RefreshLoginTest extends BaseTestCase
{
    public function test_it_should_not_refresh_login_a_user(): void
    {
        $path = 'tests/Fixtures/Api/Auth/invalid-refresh-token.json';
        $body = json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/refresh' => Http::response(
                body: $body,
                status: Response::HTTP_UNAUTHORIZED
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.refresh'),
            data: ['refresh_token' => fake()->uuid],
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }
    public function test_it_should_refresh_login_a_user(): void
    {
        $path = 'tests/Fixtures/Api/Auth/valid-refresh-token.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/refresh' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.refresh'),
            data: ['refresh_token' => fake()->uuid],
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
