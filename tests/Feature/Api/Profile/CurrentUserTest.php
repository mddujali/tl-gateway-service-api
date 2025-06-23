<?php

namespace Tests\Feature\Api\Profile;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class CurrentUserTest extends BaseTestCase
{
    use WithoutMiddleware;

    public function test_it_should_return_unauthorized(): void
    {
        $path = 'tests/Fixtures/Api/Auth/unauthenticated.json';
        $body = json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);

        Http::fake([
            config('services.auth_service_api.url') . '/api/profile' => Http::response(
                body: $body,
                status: Response::HTTP_UNAUTHORIZED
            ),
        ]);

        $response = $this->json(
            method: 'get',
            uri: route('api.profile'),
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_return_current_user_profile(): void
    {
        $path = 'tests/Fixtures/Api/Profile/current-user.json';
        $body = json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);
        $token = $this->generateToken();

        Http::fake([
            config('services.auth_service_api.url') . '/api/profile' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'get',
            uri: route('api.profile'),
            headers: [
                ...$this->headers,
                'Authorization' => 'Bearer ' . $token['data']['access_token'],
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
