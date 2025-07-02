<?php

declare(strict_types=1);

use App\Enums\AuditLogType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')
                ->nullable();
            $table->enum('type', AuditLogType::values());
            $table->text('message');
            $table->json('context')
                ->nullable();
            $table->timestamp('created_at')
                ->useCurrent();
        });

        Schema::table('audit_logs', function (Blueprint $table): void {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
