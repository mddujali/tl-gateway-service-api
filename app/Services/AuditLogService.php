<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuditLogType;
use App\Models\AuditLog;

class AuditLogService
{
    public function log(AuditLogType $type, string $message, array $context = [], ?int $userId = null): void
    {
        AuditLog::query()
            ->create([
                'user_id' => $userId,
                'type' => $type->value,
                'message' => $message,
                'context' => $context,
            ]);
    }
}
