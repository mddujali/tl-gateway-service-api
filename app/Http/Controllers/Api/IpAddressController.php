<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\IpAddress\SaveIpAddressRequest;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class IpAddressController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        return $this->handleRequest(
            request: $request,
            serviceCall: fn () => $this->makeHttpClient($request)->get($this->baseUrl()),
            successMessage: __('IP Addresses has been fetched.'),
            errorMessage: __('Unable to fetch IP Addresses.')
        );
    }

    public function store(SaveIpAddressRequest $request): JsonResponse
    {
        $context = ['payload' => $request->validated()];

        return $this->handleRequest(
            request: $request,
            serviceCall: fn () => $this->makeHttpClient($request)->post($this->baseUrl(), $request->validated()),
            successMessage: __('IP Address has been created.'),
            errorMessage: __('Unable to create IP Address.'),
            context: $context
        );
    }

    public function show(Request $request): JsonResponse
    {
        $ipAddressId = $request->route('ip_address_id');
        $context = ['ip_address_id' => $ipAddressId];

        return $this->handleRequest(
            request: $request,
            serviceCall: fn () => $this->makeHttpClient($request)->get($this->baseUrl() . '/' . $ipAddressId),
            successMessage: __('IP Address has been fetched.'),
            errorMessage: __('Unable to fetch IP Address.'),
            context: $context
        );
    }

    public function update(SaveIpAddressRequest $request): JsonResponse
    {
        $ipAddressId = $request->route('ip_address_id');
        $data = [
            'user_id' => $request->input('user_id'),
            'user_role' => $request->input('user_role'),
            ...$request->validated(),
        ];
        $context = [
            'ip_address_id' => $ipAddressId,
            'payload' => $request->validated(),
        ];

        return $this->handleRequest(
            request: $request,
            serviceCall: fn () => $this->makeHttpClient($request)->put($this->baseUrl() . '/' . $ipAddressId, $data),
            successMessage: __('IP Address has been edited.'),
            errorMessage: __('Unable to edit IP Address.'),
            context: $context
        );
    }

    private function handleRequest(
        Request $request,
        callable $serviceCall,
        string $successMessage,
        string $errorMessage,
        array $context = []
    ): JsonResponse {
        try {
            $response = $serviceCall();
        } catch (Exception $exception) {
            return $this->handleException($request, $exception, $errorMessage, $context);
        }

        return $this->handleResponse($request, $response, $successMessage, $errorMessage, $context);
    }

    private function baseUrl(): string
    {
        return config('services.ip_service_api.url') . '/api/ip-addresses';
    }

    private function makeHttpClient(Request $request): PendingRequest
    {
        return Http::asJson()->withToken($request->bearerToken());
    }

    private function handleException(Request $request, Exception $exception, string $message, array $context = []): JsonResponse
    {
        $this->handleAuditLogError($request, $exception, $message, $context);

        if ($exception instanceof ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('shared.ip_service.connection_exception')
            );
        }

        return $this->errorResponse(
            status: Response::HTTP_INTERNAL_SERVER_ERROR,
            message: __('shared.common.exception')
        );
    }

    private function handleResponse(Request $request, $response, string $successMessage, string $errorMessage, array $context = []): JsonResponse
    {
        if ($response->failed()) {
            $this->handleAuditLogError(
                request: $request,
                message: $errorMessage,
                context: $context
            );
        } else {
            $this->handleAuditLogInfo($request, $successMessage, $context);
        }

        return $this->resolveResponse($response);
    }
}
