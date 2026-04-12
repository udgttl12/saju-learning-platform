<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_set_id',
        'question_type',
        'prompt_text',
        'target_hanja_char_id',
        'choices_json',
        'answer_payload_json',
        'explanation_text',
        'hint_text',
        'sort_order',
        'points',
    ];

    protected $casts = [
        'choices_json' => 'array',
        'answer_payload_json' => 'array',
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
}
