<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    protected array $headers = [
        'Accept' => 'application/json',
    ];

    protected function generateToken(): array
    {
        $path = 'tests/Fixtures/Api/Auth/authenticated.json';

        return json_decode(file_get_contents(base_path($path)), true, 512, JSON_THROW_ON_ERROR);
    }
}
