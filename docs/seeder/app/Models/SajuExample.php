<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SajuExample extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'slug',
        'title',
        'description',
        'gender',
        'solar_birth_datetime',
        'lunar_birth_label',
        'year_stem',
        'year_branch',
        'month_stem',
        'month_branch',
        'day_stem',
        'day_branch',
        'hour_stem',
        'hour_branch',
        'chart_json',
        'difficulty_level',
        'publish_status',
        'published_at',
    ];

    protected $casts = [
        'solar_birth_datetime' => 'datetime',
        'chart_json' => 'array',
        'difficulty_level' => 'integer',
        'published_at' => 'datetime',
    ];
}
