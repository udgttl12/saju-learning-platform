<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('quiz_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()
                ->constrained('lessons', 'id', 'fk_quiz_sets_lesson_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('code', 50)->unique('uq_quiz_sets_code');
            $table->string('title', 150);
            $table->string('scope_type', 30)->default('lesson')->comment('lesson|track|review');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('difficulty_level')->default(1);
            $table->unsignedTinyInteger('pass_score')->default(70);
            $table->string('publish_status', 30)->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['lesson_id', 'publish_status'], 'idx_quiz_sets_lesson_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('quiz_sets');

    }
};
