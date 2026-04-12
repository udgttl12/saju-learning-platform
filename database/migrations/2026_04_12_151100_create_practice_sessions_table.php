<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('practice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_practice_sessions_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('hanja_char_id')
                ->constrained('hanja_chars', 'id', 'fk_practice_sessions_hanja_char_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('lesson_id')->nullable()
                ->constrained('lessons', 'id', 'fk_practice_sessions_lesson_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('practice_mode', 30)->default('trace')->comment('trace|overlay|free');
            $table->string('input_device', 30)->default('mouse')->comment('mouse|touch|pen|unknown');
            $table->string('status', 30)->default('completed')->comment('in_progress|completed|abandoned');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('duration_ms')->default(0);
            $table->unsignedTinyInteger('self_rating')->nullable()->comment('1~5');
            $table->json('session_meta_json')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'hanja_char_id'], 'idx_practice_sessions_user_hanja');
            $table->index('started_at', 'idx_practice_sessions_started_at');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('practice_sessions');

    }
};
