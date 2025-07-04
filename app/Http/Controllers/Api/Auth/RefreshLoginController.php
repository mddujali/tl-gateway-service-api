<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\RefreshLoginRequest;
use Illuminate\Http\JsonResponse;

class RefreshLoginController extends BaseAuthController
{
    public function __invoke(RefreshLoginRequest $request): JsonResponse
    {
        return $this->handleAuthenticatedRequest(
            request: $request,
            serviceCall: fn () => $this->authService->refreshToken($request->validated()),
            successMessage: __('Refresh token attempt successful.'),
            errorMessage: __('Refresh token attempt failed.')
        );
    }
}
