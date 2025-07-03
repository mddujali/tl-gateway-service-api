<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\AuditLogType;
use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use ResponseTemplate;

    public function __construct(private readonly AuditLogService $auditLogService)
    {

    }

    protected function resolveResponse(Response $response): JsonResponse
    {
        return response()->json(
            data: $response->json(),
            status: $response->status()
        );
    }

    protected function handleAuditLogError(Request | FormRequest $request, ?Exception $exception = null, string $message = 'Request failed'): void
    {
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'destination_url' => $request->url(),
        ];

        if (null !== $exception) {
            $context['exception'] = $exception::class;
            $context['message'] = $exception->getMessage();
        }

        $this->auditLogService->log(
            type: AuditLogType::ERROR,
            message: $message,
            context: $context,
        );
    }

    protected function handleAuditLogInfo(Request | FormRequest $request, string $message = 'Request successful.'): void
    {
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'destination_url' => $request->url(),
        ];

        $this->auditLogService->log(
            type: AuditLogType::INFO,
            message: $message,
            context: $context,
        );
    }
}
