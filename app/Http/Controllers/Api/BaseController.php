<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Traits\Http\Templates\Requests\Api\ResponseTemplate;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    use ResponseTemplate;

    protected function resolveResponse(Response $response): JsonResponse
    {
        return response()->json(
            data: $response->json(),
            status: $response->status()
        );
    }
}
