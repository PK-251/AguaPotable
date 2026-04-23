<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarifa extends Model
{
    protected $fillable = [
        'nombre',
        'monto',
        'descripcion',
        'vigente_desde',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'vigente_desde' => 'date',
        ];
    }

    // ── Relaciones ──────────────────────────────────

    public function padronUsuarios(): HasMany
    {
        return $this->hasMany(PadronUsuario::class);
    }
}
