<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Role;
use App\Models\User;

class UserService
{
    public function save(int $id, string $name, Role $role): User
    {
        return User::query()
            ->updateOrCreate(
                ['id' => $id],
                [
                    'id' => $id,
                    'name' => $name,
                    'role' => $role->value,
                ]
            );
    }
}
