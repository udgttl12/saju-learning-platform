<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'review_card_id',
        'user_id',
        'reviewed_at',
        'result',
        'response_ms',
        'score',
        'before_state_json',
        'after_state_json',
        'created_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'response_ms' => 'integer',
        'score' => 'decimal:2',
        'before_state_json' => 'array',
        'after_state_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function reviewCard(): BelongsTo
    {
        return $this->belongsTo(ReviewCard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
