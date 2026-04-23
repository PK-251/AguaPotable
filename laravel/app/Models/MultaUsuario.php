<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultaUsuario extends Model
{
    protected $table = 'multas_usuario';

    protected $fillable = [
        'padron_usuario_id',
        'multa_id',
        'mes',
        'monto',
        'pagada',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'pagada' => 'boolean',
        ];
    }

    // ── Relaciones ──────────────────────────────────

    public function padronUsuario(): BelongsTo
    {
        return $this->belongsTo(PadronUsuario::class);
    }

    public function multa(): BelongsTo
    {
        return $this->belongsTo(Multa::class);
    }
}
