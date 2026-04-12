<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'admin_user_id',
        'entity_type',
        'entity_id',
        'action_type',
        'diff_json',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'entity_id' => 'integer',
        'diff_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
