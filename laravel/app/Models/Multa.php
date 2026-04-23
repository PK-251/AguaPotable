<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Multa extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'monto',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    // ── Relaciones ──────────────────────────────────

    public function multasUsuario(): HasMany
    {
        return $this->hasMany(MultaUsuario::class);
    }

    public function padronUsuarios(): BelongsToMany
    {
        return $this->belongsToMany(PadronUsuario::class, 'multas_usuario')
                    ->withPivot('mes', 'monto', 'pagada')
                    ->withTimestamps();
    }
}
