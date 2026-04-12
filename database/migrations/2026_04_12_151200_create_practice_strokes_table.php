<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('practice_strokes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_session_id')
                ->constrained('practice_sessions', 'id', 'fk_practice_strokes_session_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedSmallInteger('stroke_no');
            $table->json('points_json');
            $table->json('bbox_json')->nullable();
            $table->unsignedInteger('duration_ms')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['practice_session_id', 'stroke_no'], 'uq_practice_strokes_session_stroke');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('practice_strokes');

    }
};
