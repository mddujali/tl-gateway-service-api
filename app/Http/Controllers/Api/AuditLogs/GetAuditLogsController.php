<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\AuditLogs;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Api\AuditLogs\AuditLogCollection;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class GetAuditLogsController extends BaseController
{
    public function __invoke(Request $request)
    {
        $auditLogs = AuditLog::query()->get();

        return (new AuditLogCollection($auditLogs))
            ->setMessage(__('shared.common.success'));
    }
}
