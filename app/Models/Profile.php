<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'beginner_level',
        'hanja_level',
        'daily_goal_minutes',
        'preferred_learning_style',
        'timezone',
        'onboarding_completed_at',
        'memo',
    ];

    protected $casts = [
        'daily_goal_minutes' => 'integer',
        'onboarding_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
