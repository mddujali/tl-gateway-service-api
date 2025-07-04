<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Support\Traits\Http\Auditable;
use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use ResponseTemplate;
    use Auditable;

    protected function resolveResponse(Response $response): JsonResponse
    {
        return response()->json(
            data: $response->json(),
            status: $response->status()
        );
    }

    protected function resolveAuthenticatedAttributes(Request | FormRequest $request, Response $response): void
    {
        $id = (int) $response->json('data.user.id');
        $role = $response->json('data.user.role');

        app(UserService::class)->save($id, Role::from($role));

        $request->attributes->set('user_id', $id);
    }

    protected function resolveAuthenticatedResponse(Response $response): JsonResponse
    {
        return response()->json(
            data: [
                'message' => $response->json('message'),
                'data' => $response->json('data.token'),
            ],
            status: $response->status()
        );
    }
}
