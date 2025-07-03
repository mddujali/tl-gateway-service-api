<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\AuditLogType;
use App\Services\AuditLogService;
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

    public function __construct(private readonly AuditLogService $auditLogService)
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = null;
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'destination_url' => $request->url(),
        ];

        try {
            $payload = JWTAuth::parseToken()->getPayload();

            $request->attributes->set('user_id', $userId = (int) $payload->get('sub'));
        } catch (Exception $exception) {
            $context['exception'] = $exception::class;
            $context['message'] = $exception->getMessage();

            [$errorCode, $message] = match (true) {
                $exception instanceof TokenExpiredException => ['AccessTokenExpired', __('Access token has expired.')],
                $exception instanceof TokenInvalidException => ['AccessTokenInvalid', __('Access token is invalid.')],
                $exception instanceof JWTException => ['AccessTokenUnknown', __('Access token not provided.')],
                default => ['AccessTokenError', $exception->getMessage()],
            };

            $this->auditLogService->log(
                type: AuditLogType::ERROR,
                message: $message,
                context: $context,
                userId: $userId
            );

            return $this->errorResponse(
                status: Response::HTTP_UNAUTHORIZED,
                errorCode: $errorCode,
                message: $message
            );
        }

        $this->auditLogService->log(
            type: AuditLogType::INFO,
            message: __('Access token validated successfully.'),
            context: $context,
            userId: $userId
        );

        return $next($request);
    }
}
