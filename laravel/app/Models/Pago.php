<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'padron_usuario_id',
        'user_id',
        'periodo',
        'monto_cuota',
        'monto_deuda',
        'monto_multas',
        'monto_total',
        'numero_serie',
        'estado',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'monto_cuota' => 'decimal:2',
            'monto_deuda' => 'decimal:2',
            'monto_multas' => 'decimal:2',
            'monto_total' => 'decimal:2',
        ];
    }

    // ── Relaciones ──────────────────────────────────

    public function padronUsuario(): BelongsTo
    {
        return $this->belongsTo(PadronUsuario::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ─────────────────────────────────────

    public function estaPagado(): bool
    {
        return $this->estado === 'pagado';
    }
}
