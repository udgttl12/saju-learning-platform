<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'learning_track_id',
        'status',
        'progress_percent',
        'started_at',
        'last_accessed_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percent' => 'decimal:2',
        'started_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function learningTrack(): BelongsTo
    {
        return $this->belongsTo(LearningTrack::class);
    }
}
