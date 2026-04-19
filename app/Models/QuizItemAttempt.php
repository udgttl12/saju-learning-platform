<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizItemAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'quiz_item_id',
        'question_snapshot_json',
        'user_answer_json',
        'is_correct',
        'earned_points',
        'elapsed_ms',
    ];

    protected $casts = [
        'question_snapshot_json' => 'array',
        'user_answer_json' => 'array',
        'is_correct' => 'boolean',
        'earned_points' => 'integer',
        'elapsed_ms' => 'integer',
    ];

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function quizItem(): BelongsTo
    {
        return $this->belongsTo(QuizItem::class);
    }
}
