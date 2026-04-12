<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningTrack extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'slug',
        'title',
        'short_description',
        'target_audience',
        'difficulty_level',
        'estimated_total_minutes',
        'sort_order',
        'publish_status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'difficulty_level' => 'integer',
        'estimated_total_minutes' => 'integer',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrackEnrollment::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'track_enrollments')
            ->withPivot([
                'id',
                'status',
                'progress_percent',
                'started_at',
                'last_accessed_at',
                'completed_at',
                'created_at',
                'updated_at',
            ]);
    }
}
