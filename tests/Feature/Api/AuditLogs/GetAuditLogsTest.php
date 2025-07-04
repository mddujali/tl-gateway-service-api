<?php

declare(strict_types=1);

namespace Tests\Feature\Api\AuditLogs;

use App\Models\AuditLog;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\Feature\Api\BaseTestCase;

class GetAuditLogsTest extends BaseTestCase
{
    use WithoutMiddleware;

    public function test_it_should_return_a_list_of_audit_logs(): void
    {
        AuditLog::factory(100)->create();

        $token = $this->generateToken();

        $response = $this->json(
            method: 'get',
            uri: route('api.audit-logs.index'),
            headers: [
                ...$this->headers,
                'Authorization' => 'Bearer ' . $token['data']['access_token'],
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'type',
                    'message',
                    'context',
                    'created_at',
                ]
            ]
        ]);
    }
}
