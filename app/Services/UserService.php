<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Role;
use App\Models\User;

class UserService
{
    public function save(int $id, Role $role): User
    {
        return User::query()
            ->updateOrCreate(['id' => $id], ['id' => $id, 'role' => $role->value]);
    }
}
