<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_tracks', function (Blueprint $table) {
            $table->json('unlock_rule_json')->nullable()->after('sort_order');
        });

        Schema::table('track_enrollments', function (Blueprint $table) {
            $table->unsignedTinyInteger('track_exam_best_score')->nullable()->after('progress_percent');
            $table->dateTime('passed_exam_at')->nullable()->after('completed_at');
            $table->index('passed_exam_at', 'idx_track_enrollments_passed_exam_at');
        });

        Schema::table('quiz_sets', function (Blueprint $table) {
            $table->foreignId('learning_track_id')->nullable()
                ->after('lesson_id')
                ->constrained('learning_tracks', 'id', 'fk_quiz_sets_learning_track_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->index(['learning_track_id', 'scope_type'], 'idx_quiz_sets_track_scope');
        });

        Schema::table('quiz_items', function (Blueprint $table) {
            $table->string('source_type', 30)->default('manual')->after('question_type')
                ->comment('manual|generated|personalized');
            $table->string('concept_key', 120)->nullable()->after('target_hanja_char_id');
            $table->json('meta_json')->nullable()->after('answer_payload_json');
            $table->index('concept_key', 'idx_quiz_items_concept_key');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_items', function (Blueprint $table) {
            $table->dropIndex('idx_quiz_items_concept_key');
            $table->dropColumn(['source_type', 'concept_key', 'meta_json']);
        });

        Schema::table('quiz_sets', function (Blueprint $table) {
            $table->dropIndex('idx_quiz_sets_track_scope');
            $table->dropForeign('fk_quiz_sets_learning_track_id');
            $table->dropColumn('learning_track_id');
        });

        Schema::table('track_enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_track_enrollments_passed_exam_at');
            $table->dropColumn(['track_exam_best_score', 'passed_exam_at']);
        });

        Schema::table('learning_tracks', function (Blueprint $table) {
            $table->dropColumn('unlock_rule_json');
        });
    }
};
