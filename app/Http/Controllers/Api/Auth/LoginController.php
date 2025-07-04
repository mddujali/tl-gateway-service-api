<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends BaseAuthController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        return $this->handleAuthenticatedRequest(
            request: $request,
            serviceCall: fn () => $this->authService->login($request->validated()),
            successMessage: __('Login attempt successful.'),
            errorMessage: __('Login attempt failed.')
        );
    }
}
