<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

abstract class BaseAuthController extends BaseController
{
    public function __construct(protected AuthService $authService)
    {

    }

    protected function handleRequest(
        Request $request,
        callable $serviceCall,
        string $successMessage,
        string $errorMessage
    ): JsonResponse {
        try {
            $response = $serviceCall();
        } catch (Exception $exception) {
            return $this->handleException($request, $exception, $errorMessage);
        }

        return $this->handleResponse($request, $response, $successMessage, $errorMessage);
    }

    protected function handleAuthenticatedRequest(
        Request $request,
        callable $serviceCall,
        string $successMessage,
        string $errorMessage
    ): JsonResponse {
        try {
            $response = $serviceCall();
        } catch (Exception $exception) {
            return $this->handleException($request, $exception, $errorMessage);
        }

        return $this->handleAuthenticatedResponse($request, $response, $successMessage, $errorMessage);
    }

    private function handleException(Request $request, Exception $exception, string $errorMessage): JsonResponse
    {
        $this->handleAuditLogError($request, $exception, $errorMessage);

        if ($exception instanceof ConnectionException) {
            return $this->errorResponse(
                status: HttpResponse::HTTP_SERVICE_UNAVAILABLE,
                message: __('shared.auth_service.connection_exception')
            );
        }

        return $this->errorResponse(
            status: HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            message: __('shared.common.exception')
        );
    }

    private function handleResponse(
        Request $request,
        Response $response,
        string $successMessage,
        string $errorMessage
    ): JsonResponse {
        if ($response->failed()) {
            $this->handleAuditLogError(request: $request, message: $errorMessage);

            return $this->resolveResponse($response);
        }

        $this->handleAuditLogInfo($request, $successMessage);

        return $this->resolveResponse($response);
    }

    private function handleAuthenticatedResponse(
        Request $request,
        Response $response,
        string $successMessage,
        string $errorMessage
    ): JsonResponse {
        if ($response->failed()) {
            $this->handleAuditLogError(request: $request, message: $errorMessage);

            return $this->resolveResponse($response);
        }

        $this->resolveAuthenticatedAttributes($request, $response);

        $this->handleAuditLogInfo($request, $successMessage);

        return $this->resolveAuthenticatedResponse($response);
    }
}
