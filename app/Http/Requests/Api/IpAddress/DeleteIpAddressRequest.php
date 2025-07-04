<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\IpAddress;

use App\Http\Requests\Api\BaseRequest;
use App\Models\User;

class DeleteIpAddressRequest extends BaseRequest
{
    protected function passedValidation(): void
    {
        $user = User::query()->find($this->attributes->get('user_id'));

        if ($user) {
            $this->merge([
                'user_id' => $user->id,
                'user_role' => $user->role,
            ]);
        }
    }
}
