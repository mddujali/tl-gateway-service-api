<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\IpAddress;

use App\Http\Requests\Api\BaseRequest;

class SaveIpAddressRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'ip_address' => ['required', 'ip'],
            'label' => ['required','string','max:255'],
            'comment' => ['sometimes','string','max:255'],
        ];
    }
}
