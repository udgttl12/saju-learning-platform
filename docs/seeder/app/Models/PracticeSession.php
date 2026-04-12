<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hanja_char_id',
        'lesson_id',
        'practice_mode',
        'input_device',
        'status',
        'started_at',
        'ended_at',
        'duration_ms',
        'self_rating',
        'session_meta_json',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_ms' => 'integer',
        'self_rating' => 'integer',
        'session_meta_json' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function strokes(): HasMany
    {
        return $this->hasMany(PracticeStroke::class)->orderBy('stroke_no');
    }
}
