<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends BaseAuthController
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->handleRequest(
            request: $request,
            serviceCall: fn () => $this->authService->logout($request->bearerToken()),
            successMessage: __('Logout attempt successful.'),
            errorMessage: __('Logout attempt failed.')
        );
    }
}
