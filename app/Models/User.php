<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'role',
        'status',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function trackEnrollments(): HasMany
    {
        return $this->hasMany(TrackEnrollment::class);
    }

    public function learningTracks(): BelongsToMany
    {
        return $this->belongsToMany(LearningTrack::class, 'track_enrollments')
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

    public function createdLearningTracks(): HasMany
    {
        return $this->hasMany(LearningTrack::class, 'created_by');
    }

    public function updatedLearningTracks(): HasMany
    {
        return $this->hasMany(LearningTrack::class, 'updated_by');
    }

    public function createdLessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'created_by');
    }

    public function updatedLessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'updated_by');
    }

    public function lessonAttempts(): HasMany
    {
        return $this->hasMany(LessonAttempt::class);
    }

    public function practiceSessions(): HasMany
    {
        return $this->hasMany(PracticeSession::class);
    }

    public function reviewCards(): HasMany
    {
        return $this->hasMany(ReviewCard::class);
    }

    public function reviewLogs(): HasMany
    {
        return $this->hasMany(ReviewLog::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function adminAuditLogs(): HasMany
    {
        return $this->hasMany(AdminAuditLog::class, 'admin_user_id');
    }
}
