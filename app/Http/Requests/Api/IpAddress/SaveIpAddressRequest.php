<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\IpAddress;

use App\Http\Requests\Api\BaseRequest;
use App\Models\User;
use Override;

class SaveIpAddressRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'ip_address' => ['required', 'ip'],
            'label' => ['required','string','max:255'],
            'comment' => ['sometimes','max:255'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'ip_address' => [
                'ip' => 'The ip address field must be a valid.',
            ],
        ];
    }

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
