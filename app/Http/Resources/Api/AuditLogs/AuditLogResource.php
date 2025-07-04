<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\AuditLogs;

use App\Http\Resources\Api\BaseJsonResource;
use Illuminate\Http\Request;
use Override;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read string $type
 * @property-read string $message
 * @property-read object $context
 * @property-read string $created_at
 */
class AuditLogResource extends BaseJsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'message' => $this->message,
            'context' => $this->context,
            'created_at' => $this->created_at,
        ];
    }
}
