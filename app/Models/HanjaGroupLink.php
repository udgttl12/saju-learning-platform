<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HanjaGroupLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'hanja_char_id',
        'hanja_group_id',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function hanjaChar(): BelongsTo
    {
        return $this->belongsTo(HanjaChar::class);
    }

    public function hanjaGroup(): BelongsTo
    {
        return $this->belongsTo(HanjaGroup::class);
    }
}
