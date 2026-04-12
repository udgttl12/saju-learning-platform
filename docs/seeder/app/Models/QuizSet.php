<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'code',
        'title',
        'scope_type',
        'description',
        'difficulty_level',
        'pass_score',
        'publish_status',
        'published_at',
    ];

    protected $casts = [
        'difficulty_level' => 'integer',
        'pass_score' => 'integer',
        'published_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuizItem::class)->orderBy('sort_order');
    }
}
