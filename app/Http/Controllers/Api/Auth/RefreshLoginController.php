<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\RefreshLoginRequest;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class RefreshLoginController extends BaseController
{
    public function __invoke(RefreshLoginRequest $request)
    {
        try {
            $response = Http::asJson()
                ->post(
                    url: config('services.auth_service_api.url') . '/api/auth/refresh',
                    data: $request->validated()
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError($request, $exception, __('Refresh token attempt failed.'));

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
            $this->handleAuditLogError(request: $request, message: __('Refresh token attempt failed.'));

            return $this->resolveResponse($response);
        }

        $this->resolveAuthenticatedAttributes($request, $response);

        $this->handleAuditLogInfo($request, __('Refresh token attempt successful.'));

        return $this->resolveAuthenticatedResponse($response);
    }
}
