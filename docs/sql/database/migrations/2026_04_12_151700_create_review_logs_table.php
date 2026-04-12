<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('review_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_card_id')
                ->constrained('review_cards', 'id', 'fk_review_logs_review_card_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_review_logs_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->dateTime('reviewed_at');
            $table->string('result', 20)->comment('again|hard|good|easy');
            $table->unsignedInteger('response_ms')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->json('before_state_json')->nullable();
            $table->json('after_state_json')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['review_card_id', 'reviewed_at'], 'idx_review_logs_card_reviewed');
            $table->index(['user_id', 'reviewed_at'], 'idx_review_logs_user_reviewed');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('review_logs');

    }
};
