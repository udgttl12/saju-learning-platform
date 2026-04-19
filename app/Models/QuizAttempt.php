<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_set_id',
        'score_percentage',
        'earned_points',
        'total_points',
        'total_items',
        'correct_count',
        'passed',
        'weak_points_json',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'score_percentage' => 'integer',
        'earned_points' => 'integer',
        'total_points' => 'integer',
        'total_items' => 'integer',
        'correct_count' => 'integer',
        'passed' => 'boolean',
        'weak_points_json' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quizSet(): BelongsTo
    {
        return $this->belongsTo(QuizSet::class);
    }

    public function itemAttempts(): HasMany
    {
        return $this->hasMany(QuizItemAttempt::class)->orderBy('id');
    }
}
