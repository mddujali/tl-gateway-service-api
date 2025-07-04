<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AuditLogType;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence,
            'type' => fake()->randomElement(AuditLogType::values()),
            'context' => [
                'ip_address' => fake()->ipv4,
                'user_agent' => fake()->userAgent,
            ],
        ];
    }
}
