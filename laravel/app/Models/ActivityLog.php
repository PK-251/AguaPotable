<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    /**
     * Solo tiene created_at, no updated_at.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'accion',
        'modulo',
        'ip',
    ];

    // ── Relaciones ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
