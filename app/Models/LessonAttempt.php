<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'status',
        'progress_percent',
        'latest_score',
        'best_score',
        'total_time_seconds',
        'first_started_at',
        'last_accessed_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percent' => 'decimal:2',
        'latest_score' => 'decimal:2',
        'best_score' => 'decimal:2',
        'total_time_seconds' => 'integer',
        'first_started_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
