<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonHanjaLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'lesson_id',
        'hanja_char_id',
        'relation_type',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function hanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class);
    }
}
