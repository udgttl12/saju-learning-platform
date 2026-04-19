<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_quiz_attempts_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('quiz_set_id')
                ->constrained('quiz_sets', 'id', 'fk_quiz_attempts_quiz_set_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedTinyInteger('score_percentage')->default(0);
            $table->unsignedSmallInteger('earned_points')->default(0);
            $table->unsignedSmallInteger('total_points')->default(0);
            $table->unsignedSmallInteger('total_items')->default(0);
            $table->unsignedSmallInteger('correct_count')->default(0);
            $table->boolean('passed')->default(false);
            $table->json('weak_points_json')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'quiz_set_id', 'created_at'], 'idx_quiz_attempts_user_set_created');
            $table->index(['quiz_set_id', 'passed'], 'idx_quiz_attempts_set_passed');
        });

        Schema::create('quiz_item_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')
                ->constrained('quiz_attempts', 'id', 'fk_quiz_item_attempts_attempt_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('quiz_item_id')
                ->nullable()
                ->constrained('quiz_items', 'id', 'fk_quiz_item_attempts_item_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->json('question_snapshot_json')->nullable();
            $table->json('user_answer_json')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedSmallInteger('earned_points')->default(0);
            $table->unsignedInteger('elapsed_ms')->default(0);
            $table->timestamps();

            $table->index(['quiz_attempt_id', 'is_correct'], 'idx_quiz_item_attempts_attempt_correct');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_item_attempts');
        Schema::dropIfExists('quiz_attempts');
    }
};
