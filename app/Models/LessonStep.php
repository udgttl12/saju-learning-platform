<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'step_type',
        'title',
        'content_markdown',
        'payload_json',
        'sort_order',
        'is_required',
        'estimated_minutes',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'sort_order' => 'integer',
        'is_required' => 'boolean',
        'estimated_minutes' => 'integer',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
