<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\User;
use Override;

class UserTest extends BaseModelTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = app(User::class);

        $this->table = 'users';

        $this->columns = [
            'id',
            'role',
        ];

        $this->fillable = [
            'id',
            'role',
        ];
    }
}
