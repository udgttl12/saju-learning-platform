<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_bookmarks_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('target_type', 30)->comment('lesson|hanja_char|saju_example|quiz_set|term');
            $table->unsignedBigInteger('target_id');
            $table->string('note', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'target_type', 'target_id'], 'uq_bookmarks_user_target');
            $table->index(['user_id', 'target_type'], 'idx_bookmarks_user_type');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('bookmarks');

    }
};
