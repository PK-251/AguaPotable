<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PadronUsuario extends Model
{
    protected $table = 'padron_usuarios';

    protected $fillable = ['codigo', 'nombre', 'apellido', 'direccion', 'estado', 'tarifa_id'];

    // Relación: un usuario tiene muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    // Relación: un usuario tiene muchas multas
    public function multas()
    {
        return $this->belongsToMany(Multa::class, 'multas_usuario');
    }

    /**
     * Multas asignadas en la tabla puente (incluye mes, monto y estado pagada).
     */
    public function multasUsuario(): HasMany
    {
        return $this->hasMany(MultaUsuario::class, 'padron_usuario_id');
    }

    // Relación: tiene una tarifa
    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }
}
