<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HanjaGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_type',
        'code',
        'name',
        'description',
        'sort_order',
        'is_core',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_core' => 'boolean',
    ];

    public function groupLinks(): HasMany
    {
        return $this->hasMany(HanjaGroupLink::class)->orderBy('sort_order');
    }

    public function hanjaChars(): BelongsToMany
    {
        return $this->belongsToMany(HanjaChar::class, 'hanja_group_links')
            ->withPivot(['id', 'sort_order', 'created_at'])
            ->orderByPivot('sort_order');
    }
}
