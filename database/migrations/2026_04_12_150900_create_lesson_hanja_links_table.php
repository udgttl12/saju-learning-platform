<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('lesson_hanja_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('lessons', 'id', 'fk_lesson_hanja_links_lesson_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('hanja_char_id')
                ->constrained('hanja_chars', 'id', 'fk_lesson_hanja_links_hanja_char_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('relation_type', 30)->default('primary')->comment('primary|secondary|quiz_target|example');
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['lesson_id', 'hanja_char_id', 'relation_type'], 'uq_lesson_hanja_links_lesson_char_relation');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('lesson_hanja_links');

    }
};
