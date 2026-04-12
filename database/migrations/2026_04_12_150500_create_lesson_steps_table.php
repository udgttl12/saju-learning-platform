<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('lesson_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('lessons', 'id', 'fk_lesson_steps_lesson_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('step_type', 30)->comment('intro|explanation|stroke_order|guided_practice|free_practice|quiz|summary');
            $table->string('title', 150);
            $table->longText('content_markdown')->nullable();
            $table->json('payload_json')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_required')->default(true);
            $table->unsignedSmallInteger('estimated_minutes')->default(3);
            $table->timestamps();

            $table->unique(['lesson_id', 'sort_order'], 'uq_lesson_steps_lesson_sort');
            $table->index('step_type', 'idx_lesson_steps_step_type');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('lesson_steps');

    }
};
