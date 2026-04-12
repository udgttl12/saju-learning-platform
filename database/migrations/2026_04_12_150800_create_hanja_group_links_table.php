<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('hanja_group_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hanja_char_id')
                ->constrained('hanja_chars', 'id', 'fk_hanja_group_links_hanja_char_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('hanja_group_id')
                ->constrained('hanja_groups', 'id', 'fk_hanja_group_links_hanja_group_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['hanja_char_id', 'hanja_group_id'], 'uq_hanja_group_links_char_group');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('hanja_group_links');

    }
};
