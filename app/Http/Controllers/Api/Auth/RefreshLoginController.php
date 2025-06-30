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
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('shared.auth_service.connection_exception')
            );
        } catch (Exception) {
            return $this->errorResponse(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: __('shared.common.exception')
            );
        }

        return $this->resolveResponse($response);
    }
}
