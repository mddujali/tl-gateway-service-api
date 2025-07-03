<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\IpAddress\SaveIpAddressRequest;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class IpAddressController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->withToken($request->bearerToken())
                ->get(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses',
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError($request, $exception, __('Unable to fetch IP Addresses.'));

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

        $this->handleAuditLogInfo($request, __('IP Addresses has been fetched.'));

        return $this->resolveResponse($response);
    }

    public function store(SaveIpAddressRequest $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->withToken($request->bearerToken())
                ->post(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses',
                    data: $request->validated()
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError(
                $request,
                $exception,
                __('Unable to create IP Address.'),
                ['payload' => $request->validated()]
            );

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

        $this->handleAuditLogInfo(
            $request,
            __('IP Address has been created.'),
            ['payload' => $request->validated()]
        );

        return $this->resolveResponse($response);
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->withToken($request->bearerToken())
                ->get(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses/' . $request->route('ip_address_id'),
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError(
                $request,
                $exception,
                __('Unable to fetch IP Address.'),
                ['ip_address_id' => $request->route('ip_address_id')]
            );

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

        $this->handleAuditLogInfo(
            $request,
            __('IP Address has been fetched.'),
            ['ip_address_id' => $request->route('ip_address_id')]
        );

        return $this->resolveResponse($response);
    }

    public function update(SaveIpAddressRequest $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->withToken($request->bearerToken())
                ->put(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses/' . $request->route('ip_address_id'),
                    data: [
                        'user_id' => $request->input('user_id'),
                        'user_role' => $request->input('user_role'),
                        ...$request->validated(),
                    ]
                );
        } catch (Exception $exception) {
            $this->handleAuditLogError(
                $request,
                $exception,
                __('Unable to edit IP Address.'),
                [
                    'ip_address_id' => $request->route('ip_address_id'),
                    'payload' => $request->validated(),
                ]
            );

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

        $this->handleAuditLogInfo(
            $request,
            __('IP Address has been edited.'),
            [
                'ip_address_id' => $request->route('ip_address_id'),
                'payload' => $request->validated(),
            ]
        );

        return $this->resolveResponse($response);
    }
}
