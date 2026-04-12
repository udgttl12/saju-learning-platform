<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrokeTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'hanja_char_id',
        'version_no',
        'template_status',
        'template_format',
        'canvas_width',
        'canvas_height',
        'stroke_count',
        'svg_path_json',
        'guide_meta_json',
        'source_note',
        'is_primary',
    ];

    protected $casts = [
        'version_no' => 'integer',
        'canvas_width' => 'integer',
        'canvas_height' => 'integer',
        'stroke_count' => 'integer',
        'svg_path_json' => 'array',
        'guide_meta_json' => 'array',
        'is_primary' => 'boolean',
    ];

    public function hanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class);
    }
}
