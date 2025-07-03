<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class LogoutController extends BaseController
{
    public function __invoke(Request $request)
    {
        try {
            $response = Http::asJson()
                ->withToken($request->bearerToken())
                ->post(
                    url: config('services.auth_service_api.url') . '/api/auth/logout',
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError($request, $exception, __('Logout attempt failed.'));

            if ($exception instanceof ConnectionException) {
                return $this->errorResponse(
                    status: Response::HTTP_SERVICE_UNAVAILABLE,
                    message: __('shared.auth_service.connection_exception')
                );
            }

            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('shared.common.exception')
            );
        }

        if ($response->failed()) {
            $this->handleAuditLogError(request: $request, message: __('Logout attempt failed.'));
        } else {
            $this->handleAuditLogInfo($request, __('Logout attempt successful.'));
        }

        return $this->resolveResponse($response);
    }
}
