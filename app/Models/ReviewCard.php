<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hanja_char_id',
        'source_type',
        'source_id',
        'stage',
        'ease_factor',
        'interval_days',
        'repetitions',
        'due_at',
        'last_result',
        'last_reviewed_at',
    ];

    protected $casts = [
        'source_id' => 'integer',
        'ease_factor' => 'decimal:2',
        'interval_days' => 'integer',
        'repetitions' => 'integer',
        'due_at' => 'datetime',
        'last_reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ReviewLog::class)->orderByDesc('reviewed_at');
    }
}
