<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('track_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_track_enrollments_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('learning_track_id')
                ->constrained('learning_tracks', 'id', 'fk_track_enrollments_learning_track_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('status', 30)->default('active')->comment('active|paused|completed');
            $table->decimal('progress_percent', 5, 2)->default(0.00);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('last_accessed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'learning_track_id'], 'uq_track_enrollments_user_track');
            $table->index(['status', 'last_accessed_at'], 'idx_track_enrollments_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('track_enrollments');

    }
};
