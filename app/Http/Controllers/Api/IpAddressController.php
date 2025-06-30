<?php

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
    public function index(): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->get(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses',
                );
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('Failed to connect to the IP service.')
            );
        } catch (Exception) {
            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('Something went wrong, please try again later.')
            );
        }

        return $this->resolveResponse($response);
    }

    public function store(SaveIpAddressRequest $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->post(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses',
                    data: $request->validated()
                );
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('Failed to connect to the IP service.')
            );
        } catch (Exception) {
            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('Something went wrong, please try again later.')
            );
        }

        return $this->resolveResponse($response);
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->get(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses/' . $request->route('ip_address_id'),
                );
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('Failed to connect to the IP service.')
            );
        } catch (Exception) {
            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('Something went wrong, please try again later.')
            );
        }

        return $this->resolveResponse($response);
    }

    public function update(SaveIpAddressRequest $request): JsonResponse
    {
        try {
            $response = Http::asJson()
                ->put(
                    url: config('services.ip_service_api.url') . '/api/ip-addresses/' . $request->route('ip_address_id'),
                    data: $request->validated()
                );
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('Failed to connect to the IP service.')
            );
        } catch (Exception) {
            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('Something went wrong, please try again later.')
            );
        }

        return $this->resolveResponse($response);
    }
}
