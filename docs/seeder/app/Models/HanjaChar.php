<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HanjaChar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'char_value',
        'slug',
        'reading_ko',
        'meaning_ko',
        'category',
        'element',
        'yin_yang',
        'structure_note',
        'mnemonic_text',
        'usage_in_saju',
        'stroke_count',
        'is_core',
        'publish_status',
        'published_at',
    ];

    protected $casts = [
        'stroke_count' => 'integer',
        'is_core' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function groupLinks(): HasMany
    {
        return $this->hasMany(HanjaGroupLink::class)->orderBy('sort_order');
    }

    public function lessonLinks(): HasMany
    {
        return $this->hasMany(LessonHanjaLink::class)->orderBy('sort_order');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(HanjaGroup::class, 'hanja_group_links')
            ->withPivot(['id', 'sort_order', 'created_at'])
            ->orderByPivot('sort_order');
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_hanja_links')
            ->withPivot(['id', 'relation_type', 'sort_order', 'created_at'])
            ->orderByPivot('sort_order');
    }

    public function strokeTemplates(): HasMany
    {
        return $this->hasMany(StrokeTemplate::class)->orderByDesc('is_primary')->orderByDesc('version_no');
    }

    public function practiceSessions(): HasMany
    {
        return $this->hasMany(PracticeSession::class);
    }

    public function quizItems(): HasMany
    {
        return $this->hasMany(QuizItem::class, 'target_hanja_char_id');
    }

    public function reviewCards(): HasMany
    {
        return $this->hasMany(ReviewCard::class);
    }
}
