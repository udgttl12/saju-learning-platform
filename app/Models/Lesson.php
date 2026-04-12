<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'learning_track_id',
        'code',
        'slug',
        'title',
        'objective',
        'summary',
        'lesson_type',
        'difficulty_level',
        'estimated_minutes',
        'unlock_rule_json',
        'sort_order',
        'publish_status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'difficulty_level' => 'integer',
        'estimated_minutes' => 'integer',
        'sort_order' => 'integer',
        'unlock_rule_json' => 'array',
        'published_at' => 'datetime',
    ];

    public function learningTrack(): BelongsTo
    {
        return $this->belongsTo(LearningTrack::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(LessonStep::class)->orderBy('sort_order');
    }

    public function lessonHanjaLinks(): HasMany
    {
        return $this->hasMany(LessonHanjaLink::class)->orderBy('sort_order');
    }

    public function hanjaChars(): BelongsToMany
    {
        return $this->belongsToMany(HanjaChar::class, 'lesson_hanja_links')
            ->withPivot(['id', 'relation_type', 'sort_order', 'created_at'])
            ->orderByPivot('sort_order');
    }

    public function quizSets(): HasMany
    {
        return $this->hasMany(QuizSet::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(LessonAttempt::class);
    }

    public function practiceSessions(): HasMany
    {
        return $this->hasMany(PracticeSession::class);
    }
}
