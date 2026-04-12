<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('hanja_chars', function (Blueprint $table) {
            $table->id();
            $table->string('char_value', 10)->unique('uq_hanja_chars_char_value');
            $table->string('slug', 100)->unique('uq_hanja_chars_slug');
            $table->string('reading_ko', 50);
            $table->string('meaning_ko', 120);
            $table->string('category', 30)->comment('five_elements|heavenly_stems|earthly_branches|term');
            $table->string('element', 20)->default('none')->comment('wood|fire|earth|metal|water|none');
            $table->string('yin_yang', 20)->default('neutral')->comment('yang|yin|neutral');
            $table->string('structure_note', 120)->nullable();
            $table->text('mnemonic_text')->nullable();
            $table->text('usage_in_saju')->nullable();
            $table->unsignedTinyInteger('stroke_count')->nullable();
            $table->boolean('is_core')->default(true);
            $table->string('publish_status', 30)->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'element'], 'idx_hanja_chars_category_element');
            $table->index('publish_status', 'idx_hanja_chars_publish_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('hanja_chars');

    }
};
