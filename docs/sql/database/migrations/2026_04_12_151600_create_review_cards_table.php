<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('review_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_review_cards_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('hanja_char_id')
                ->constrained('hanja_chars', 'id', 'fk_review_cards_hanja_char_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('source_type', 30)->default('lesson')->comment('lesson|quiz|practice|manual');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('stage', 30)->default('new')->comment('new|learning|reviewing|lapsed|mastered');
            $table->decimal('ease_factor', 4, 2)->default(2.50);
            $table->unsignedInteger('interval_days')->default(0);
            $table->unsignedInteger('repetitions')->default(0);
            $table->dateTime('due_at')->nullable();
            $table->string('last_result', 20)->nullable()->comment('again|hard|good|easy');
            $table->dateTime('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'hanja_char_id'], 'uq_review_cards_user_hanja');
            $table->index(['due_at', 'stage'], 'idx_review_cards_due_stage');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('review_cards');

    }
};
