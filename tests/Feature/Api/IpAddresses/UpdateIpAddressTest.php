<?php

declare(strict_types=1);

namespace Tests\Feature\Api\IpAddresses;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class UpdateIpAddressTest extends BaseTestCase
{
    use WithoutMiddleware;

    public function test_it_should_return_service_unavailable(): void
    {
        $path = 'tests/Fixtures/Api/IpAddresses/service-unavailable.json';
        $body = file_get_contents(base_path($path));
        $token = $this->generateToken();

        Http::fake([
            config('services.ip_service_api.url') . '/api/ip-addresses/1' => Http::response(
                body: $body,
                status: Response::HTTP_SERVICE_UNAVAILABLE
            ),
        ]);

        $response = $this->json(
            method: 'put',
            uri: route('api.ip-addresses.update', ['ip_address_id' => 1]),
            data: $this->requestData(),
            headers: [
                ...$this->headers,
                'Authorization' => 'Bearer ' . $token['data']['access_token'],
            ]
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_update_ip_address(): void
    {
        $path = 'tests/Fixtures/Api/IpAddresses/ip-address-edited.json';
        $body = file_get_contents(base_path($path));
        $token = $this->generateToken();

        Http::fake([
            config('services.ip_service_api.url') . '/api/ip-addresses/1' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'put',
            uri: route('api.ip-addresses.update', ['ip_address_id' => 1]),
            data: $this->requestData(),
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
                'ip_address',
                'label',
                'comment',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    private function requestData(): array
    {
        return [
            'ip_address' => '150.82.242.195',
            'label' => 'est - edited',
            'comment' => 'Quo quia reiciendis sunt qui aut tenetur.',
        ];
    }
}
