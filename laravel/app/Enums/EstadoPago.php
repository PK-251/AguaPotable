<?php

namespace App\Enums;

/**
 * Valores alineados a la migración: enum en tabla pagos.
 */
enum EstadoPago: string
{
    case Pagado = 'pagado';
    case Pendiente = 'pendiente';
}
