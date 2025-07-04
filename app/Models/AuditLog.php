<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'context',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'json',
        ];
    }

    protected function context(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode((string) $value, true, 512, JSON_THROW_ON_ERROR),
            set: fn ($value) => json_encode($value, JSON_THROW_ON_ERROR)
        );
    }
}
