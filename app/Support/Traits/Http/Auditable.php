<?php

declare(strict_types=1);

namespace App\Support\Traits\Http;

use App\Enums\AuditLogType;
use App\Services\AuditLogService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

trait Auditable
{
    protected function handleAuditLogError(Request | FormRequest $request, ?Exception $exception = null, string $message = 'Request failed', array $context = []): void
    {
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'destination_url' => $request->url(),
            ...$context,
        ];

        if (null !== $exception) {
            $context['exception'] = $exception::class;
            $context['message'] = $exception->getMessage();
        }

        app(AuditLogService::class)->log(
            type: AuditLogType::ERROR,
            message: $message,
            context: $context,
            userId: $request->attributes->get('user_id') ? (int) $request->attributes->get('user_id') : null,
        );
    }

    protected function handleAuditLogInfo(Request | FormRequest $request, string $message = 'Request successful.', array $context = []): void
    {
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'destination_url' => $request->url(),
            ...$context,
        ];

        app(AuditLogService::class)->log(
            type: AuditLogType::INFO,
            message: $message,
            context: $context,
            userId: $request->attributes->get('user_id') ? (int) $request->attributes->get('user_id') : null,
        );
    }
}
