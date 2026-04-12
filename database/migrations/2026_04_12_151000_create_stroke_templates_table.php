<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('stroke_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hanja_char_id')
                ->constrained('hanja_chars', 'id', 'fk_stroke_templates_hanja_char_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedSmallInteger('version_no')->default(1);
            $table->string('template_status', 30)->default('draft')->comment('draft|ready|archived');
            $table->string('template_format', 30)->default('svg_json');
            $table->unsignedSmallInteger('canvas_width')->default(512);
            $table->unsignedSmallInteger('canvas_height')->default(512);
            $table->unsignedTinyInteger('stroke_count')->nullable();
            $table->json('svg_path_json')->nullable();
            $table->json('guide_meta_json')->nullable();
            $table->string('source_note', 255)->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();

            $table->unique(['hanja_char_id', 'version_no'], 'uq_stroke_templates_char_version');
            $table->index(['template_status', 'is_primary'], 'idx_stroke_templates_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('stroke_templates');

    }
};
