<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('lesson_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_lesson_attempts_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('lesson_id')
                ->constrained('lessons', 'id', 'fk_lesson_attempts_lesson_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('status', 30)->default('not_started')->comment('not_started|in_progress|completed|mastered');
            $table->decimal('progress_percent', 5, 2)->default(0.00);
            $table->decimal('latest_score', 5, 2)->nullable();
            $table->decimal('best_score', 5, 2)->nullable();
            $table->unsignedInteger('total_time_seconds')->default(0);
            $table->dateTime('first_started_at')->nullable();
            $table->dateTime('last_accessed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id'], 'uq_lesson_attempts_user_lesson');
            $table->index(['status', 'last_accessed_at'], 'idx_lesson_attempts_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('lesson_attempts');

    }
};
