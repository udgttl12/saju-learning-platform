<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_track_id')
                ->constrained('learning_tracks', 'id', 'fk_lessons_learning_track_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('code', 50)->unique('uq_lessons_code');
            $table->string('slug', 120)->unique('uq_lessons_slug');
            $table->string('title', 150);
            $table->text('objective')->nullable();
            $table->string('summary', 255)->nullable();
            $table->string('lesson_type', 30)->default('concept')->comment('concept|hanja_card|practice|quiz|example_chart');
            $table->unsignedTinyInteger('difficulty_level')->default(1);
            $table->unsignedSmallInteger('estimated_minutes')->default(10);
            $table->json('unlock_rule_json')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->string('publish_status', 30)->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users', 'id', 'fk_lessons_created_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users', 'id', 'fk_lessons_updated_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['learning_track_id', 'sort_order'], 'idx_lessons_track_order');
            $table->index(['publish_status', 'published_at'], 'idx_lessons_publish_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('lessons');

    }
};
