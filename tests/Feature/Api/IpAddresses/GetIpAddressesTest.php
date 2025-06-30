<?php

namespace Tests\Feature\Api\IpAddresses;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Api\BaseTestCase;

class GetIpAddressesTest extends BaseTestCase
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
            method: 'get',
            uri: route('api.ip-addresses.index'),
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertExactJsonStructure([
            'message',
            'error_code',
            'errors',
        ]);
    }

    public function test_it_should_get_a_list_of_ip_addresses(): void
    {
        $path = 'tests/Fixtures/Api/IpAddresses/ip-addresses.json';
        $body = file_get_contents(base_path($path));

        Http::fake([
            config('services.ip_service_api.url') . '/api/ip-addresses' => Http::response(
                body: $body,
                status: Response::HTTP_OK
            ),
        ]);

        $response = $this->json(
            method: 'get',
            uri: route('api.ip-addresses.index'),
            headers: $this->headers
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'ip_address',
                    'label',
                    'comment',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }
}
