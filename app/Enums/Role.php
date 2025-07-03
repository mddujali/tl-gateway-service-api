<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super-admin';

    case REGULAR_USER = 'regular-user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
