<?php

namespace Tests\Feature\Api\IpAddresses;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class StoreIpAddressTest extends BaseTestCase
{
    public function test_it_should_return_service_unavailable(): void
    {
        $path = 'tests/Fixtures/Api/IpAddresses/service-unavailable.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.ip_service_api.url') . '/api/ip-addresses' => Http::response(
                body: $body,
                status: Response::HTTP_SERVICE_UNAVAILABLE
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.ip-addresses.store'),
            data: $this->requestData(),
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_store_ip_address(): void
    {
        $path = 'tests/Fixtures/Api/IpAddresses/ip-address.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.ip_service_api.url') . '/api/ip-addresses' => Http::response(
                body: $body,
                status: Response::HTTP_CREATED
            ),
        ]);

        $response = $this->json(
            method: 'post',
            uri: route('api.ip-addresses.store'),
            data: $this->requestData(),
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_CREATED);
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
            'ip_address' => '150.82.242.194',
            'label' => 'est',
            'comment' => 'Quo quia reiciendis sunt qui aut tenetur.',
        ];
    }
}
