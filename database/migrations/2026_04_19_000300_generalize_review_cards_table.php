<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->rebuildForSqlite();

            return;
        }

        Schema::table('review_cards', function (Blueprint $table) {
            $table->string('target_type', 30)->default('hanja')->after('user_id')
                ->comment('hanja|concept');
            $table->string('concept_key', 120)->nullable()->after('target_type');
            $table->text('prompt_text')->nullable()->after('concept_key');
            $table->json('answer_payload_json')->nullable()->after('prompt_text');
            $table->json('meta_json')->nullable()->after('answer_payload_json');
            $table->index(['user_id', 'target_type', 'concept_key'], 'idx_review_cards_user_target_concept');
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE review_cards DROP FOREIGN KEY fk_review_cards_hanja_char_id');
            DB::statement('ALTER TABLE review_cards MODIFY hanja_char_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE review_cards ADD CONSTRAINT fk_review_cards_hanja_char_id FOREIGN KEY (hanja_char_id) REFERENCES hanja_chars(id) ON DELETE CASCADE ON UPDATE CASCADE');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('DELETE FROM review_cards WHERE target_type = "concept"');
            $this->rebuildForSqliteDown();

            return;
        }

        if ($driver === 'mysql') {
            DB::statement('DELETE FROM review_cards WHERE target_type = "concept"');
            DB::statement('ALTER TABLE review_cards DROP FOREIGN KEY fk_review_cards_hanja_char_id');
            DB::statement('ALTER TABLE review_cards MODIFY hanja_char_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE review_cards ADD CONSTRAINT fk_review_cards_hanja_char_id FOREIGN KEY (hanja_char_id) REFERENCES hanja_chars(id) ON DELETE CASCADE ON UPDATE CASCADE');
        }

        Schema::table('review_cards', function (Blueprint $table) {
            $table->dropIndex('idx_review_cards_user_target_concept');
            $table->dropColumn([
                'target_type',
                'concept_key',
                'prompt_text',
                'answer_payload_json',
                'meta_json',
            ]);
        });
    }

    private function rebuildForSqlite(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('DROP INDEX IF EXISTS uq_review_cards_user_hanja');
        DB::statement('DROP INDEX IF EXISTS idx_review_cards_due_stage');
        DB::statement('DROP INDEX IF EXISTS idx_review_cards_user_target_concept');
        Schema::rename('review_cards', 'review_cards_old');

        Schema::create('review_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_review_cards_user_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('target_type', 30)->default('hanja')->comment('hanja|concept');
            $table->string('concept_key', 120)->nullable();
            $table->text('prompt_text')->nullable();
            $table->json('answer_payload_json')->nullable();
            $table->json('meta_json')->nullable();
            $table->foreignId('hanja_char_id')
                ->nullable()
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
            $table->index(['user_id', 'target_type', 'concept_key'], 'idx_review_cards_user_target_concept');
        });

        DB::statement(<<<'SQL'
            INSERT INTO review_cards (
                id, user_id, target_type, concept_key, prompt_text, answer_payload_json, meta_json,
                hanja_char_id, source_type, source_id, stage, ease_factor, interval_days, repetitions,
                due_at, last_result, last_reviewed_at, created_at, updated_at
            )
            SELECT
                id, user_id, 'hanja', NULL, NULL, NULL, NULL,
                hanja_char_id, source_type, source_id, stage, ease_factor, interval_days, repetitions,
                due_at, last_result, last_reviewed_at, created_at, updated_at
            FROM review_cards_old
        SQL);

        Schema::drop('review_cards_old');
        DB::statement('PRAGMA foreign_keys = ON');
    }

    private function rebuildForSqliteDown(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('DROP INDEX IF EXISTS uq_review_cards_user_hanja');
        DB::statement('DROP INDEX IF EXISTS idx_review_cards_due_stage');
        DB::statement('DROP INDEX IF EXISTS idx_review_cards_user_target_concept');
        Schema::rename('review_cards', 'review_cards_old');

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

        DB::statement(<<<'SQL'
            INSERT INTO review_cards (
                id, user_id, hanja_char_id, source_type, source_id, stage, ease_factor,
                interval_days, repetitions, due_at, last_result, last_reviewed_at, created_at, updated_at
            )
            SELECT
                id, user_id, hanja_char_id, source_type, source_id, stage, ease_factor,
                interval_days, repetitions, due_at, last_result, last_reviewed_at, created_at, updated_at
            FROM review_cards_old
        SQL);

        Schema::drop('review_cards_old');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
