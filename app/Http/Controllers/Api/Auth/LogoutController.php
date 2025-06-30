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
        } catch (ConnectionException) {
            return $this->errorResponse(
                status: Response::HTTP_SERVICE_UNAVAILABLE,
                message: __('Failed to connect to the authentication service.')
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
