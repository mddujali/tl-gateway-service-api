<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureAccessTokenIsValid
{
    use ResponseTemplate;

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();

            $request->attributes->set('user_id', $payload->get('sub'));
        } catch (Exception $exception) {
            return match (true) {
                $exception instanceof TokenExpiredException => $this->errorResponse(
                    status: Response::HTTP_UNAUTHORIZED,
                    errorCode: 'AccessTokenExpired',
                    message: __('Access token has expired.')
                ),
                $exception instanceof TokenInvalidException => $this->errorResponse(
                    status: Response::HTTP_UNAUTHORIZED,
                    errorCode: 'AccessTokenInvalid',
                    message: __('Access token is invalid.')
                ),
                $exception instanceof JWTException => $this->errorResponse(
                    status: Response::HTTP_UNAUTHORIZED,
                    errorCode: 'AccessTokenUnknown',
                    message: __('Access token not provided.')
                ),
                default => $this->errorResponse(
                    status: Response::HTTP_UNAUTHORIZED,
                    errorCode: 'AccessTokenError',
                    message: $exception->getMessage()
                ),
            };
        }

        return $next($request);
    }
}
