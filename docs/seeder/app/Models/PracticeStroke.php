<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeStroke extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'practice_session_id',
        'stroke_no',
        'points_json',
        'bbox_json',
        'duration_ms',
        'created_at',
    ];

    protected $casts = [
        'stroke_no' => 'integer',
        'points_json' => 'array',
        'bbox_json' => 'array',
        'duration_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    public function practiceSession(): BelongsTo
    {
        return $this->belongsTo(PracticeSession::class);
    }
}
