<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_set_id',
        'question_type',
        'source_type',
        'prompt_text',
        'target_hanja_char_id',
        'concept_key',
        'choices_json',
        'answer_payload_json',
        'meta_json',
        'explanation_text',
        'hint_text',
        'sort_order',
        'points',
    ];

    protected $casts = [
        'choices_json' => 'array',
        'answer_payload_json' => 'array',
        'meta_json' => 'array',
        'sort_order' => 'integer',
        'points' => 'integer',
    ];

    public function quizSet(): BelongsTo
    {
        return $this->belongsTo(QuizSet::class);
    }

    public function targetHanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class, 'target_hanja_char_id');
    }

    public function itemAttempts(): HasMany
    {
        return $this->hasMany(QuizItemAttempt::class);
    }
}
