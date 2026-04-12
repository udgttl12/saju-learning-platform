<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('saju_examples', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_saju_examples_code');
            $table->string('slug', 120)->unique('uq_saju_examples_slug');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('gender', 20)->default('unknown')->comment('male|female|unknown');
            $table->dateTime('solar_birth_datetime')->nullable();
            $table->string('lunar_birth_label', 100)->nullable();
            $table->string('year_stem', 10);
            $table->string('year_branch', 10);
            $table->string('month_stem', 10);
            $table->string('month_branch', 10);
            $table->string('day_stem', 10);
            $table->string('day_branch', 10);
            $table->string('hour_stem', 10)->nullable();
            $table->string('hour_branch', 10)->nullable();
            $table->json('chart_json')->nullable();
            $table->unsignedTinyInteger('difficulty_level')->default(1);
            $table->string('publish_status', 30)->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['publish_status', 'difficulty_level'], 'idx_saju_examples_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('saju_examples');

    }
};
