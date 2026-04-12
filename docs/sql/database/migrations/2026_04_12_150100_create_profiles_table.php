<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique('uq_profiles_user_id')
                ->constrained('users', 'id', 'fk_profiles_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('display_name', 80);
            $table->string('beginner_level', 30)->default('absolute_beginner')->comment('absolute_beginner|beginner|returning');
            $table->string('hanja_level', 30)->default('none')->comment('none|basic|intermediate');
            $table->unsignedSmallInteger('daily_goal_minutes')->default(15);
            $table->string('preferred_learning_style', 30)->default('balanced')->comment('balanced|reading|writing|quiz');
            $table->string('timezone', 50)->default('Asia/Seoul');
            $table->dateTime('onboarding_completed_at')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('profiles');

    }
};
