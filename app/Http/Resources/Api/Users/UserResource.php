<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Users;

use App\Http\Resources\Api\BaseJsonResource;
use Illuminate\Http\Request;
use Override;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $role
 */
class UserResource extends BaseJsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
        ];
    }
}
