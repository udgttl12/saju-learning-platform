<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->nullable()
                ->constrained('users', 'id', 'fk_admin_audit_logs_admin_user_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('entity_type', 50);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action_type', 50);
            $table->json('diff_json')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['admin_user_id', 'created_at'], 'idx_admin_audit_logs_admin_created');
            $table->index(['entity_type', 'entity_id'], 'idx_admin_audit_logs_entity');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('admin_audit_logs');

    }
};
