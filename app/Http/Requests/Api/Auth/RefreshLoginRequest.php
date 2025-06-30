<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class RefreshLoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => 'required',
        ];
    }
}
