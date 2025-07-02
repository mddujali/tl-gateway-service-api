<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\AuditLog;
use Override;

class AuditLogTest extends BaseModelTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = app(AuditLog::class);

        $this->table = 'audit_logs';

        $this->columns = [
            'id',
            'user_id',
            'type',
            'message',
            'context',
            'created_at',
        ];

        $this->fillable = [
            'user_id',
            'type',
            'message',
            'context',
        ];
    }
}
