<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('learning_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_learning_tracks_code');
            $table->string('slug', 100)->unique('uq_learning_tracks_slug');
            $table->string('title', 120);
            $table->string('short_description', 255)->nullable();
            $table->string('target_audience', 50)->default('adult_hobby_beginner');
            $table->unsignedTinyInteger('difficulty_level')->default(1);
            $table->unsignedSmallInteger('estimated_total_minutes')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->string('publish_status', 30)->default('draft')->comment('draft|published|archived');
            $table->dateTime('published_at')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users', 'id', 'fk_learning_tracks_created_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users', 'id', 'fk_learning_tracks_updated_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['publish_status', 'sort_order'], 'idx_learning_tracks_status_order');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('learning_tracks');

    }
};
