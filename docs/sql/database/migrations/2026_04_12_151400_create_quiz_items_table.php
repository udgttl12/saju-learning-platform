<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('quiz_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_set_id')
                ->constrained('quiz_sets', 'id', 'fk_quiz_items_quiz_set_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('question_type', 30)->comment('multiple_choice|true_false|short_answer|self_check');
            $table->text('prompt_text');
            $table->foreignId('target_hanja_char_id')->nullable()
                ->constrained('hanja_chars', 'id', 'fk_quiz_items_target_hanja_char_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->json('choices_json')->nullable();
            $table->json('answer_payload_json');
            $table->text('explanation_text')->nullable();
            $table->string('hint_text', 255)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->unsignedSmallInteger('points')->default(10);
            $table->timestamps();

            $table->unique(['quiz_set_id', 'sort_order'], 'uq_quiz_items_set_sort');
            $table->index('target_hanja_char_id', 'idx_quiz_items_target_hanja');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('quiz_items');

    }
};
