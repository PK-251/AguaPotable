<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteMensual extends Model
{
    protected $table = 'reportes_mensuales';

    protected $fillable = [
        'periodo',
        'total_ingresos',
        'total_egresos',
        'total_pendientes',
        'balance',
        'estado',
        'aprobado_por',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'total_ingresos' => 'decimal:2',
            'total_egresos' => 'decimal:2',
            'total_pendientes' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    // ── Relaciones ──────────────────────────────────

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    // ── Helpers ─────────────────────────────────────

    public function estaAprobado(): bool
    {
        return $this->estado === 'aprobado';
    }
}
