<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class LogoutTest extends BaseTestCase
{
    use WithoutMiddleware;

    public function test_it_should_return_unauthorized(): void
    {
        $path = 'tests/Fixtures/Api/Auth/unauthenticated.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/logout' => Http::response(
                body: $body,
                status: Response::HTTP_UNAUTHORIZED
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.logout'),
            headers: [
                ...$this->headers,
                'Authorization' => 'Bearer ' . fake()->uuid,
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_logout_a_user(): void
    {
        $path = 'tests/Fixtures/Api/Auth/invalidate-token.json';
        $body = json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);
        $token = $this->generateToken();

        Http::fake([
            config('services.auth_service_api.url') . '/api/auth/logout' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.auth.logout'),
            headers: [
                ...$this->headers,
                'Authorization' => 'Bearer ' . $token['data']['access_token'],
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJsonStructure([
            'message',
            'data',
        ]);
    }

    private function generateToken(): array
    {
        $path = 'tests/Fixtures/Api/Auth/authenticated.json';

        return json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);
    }
}
