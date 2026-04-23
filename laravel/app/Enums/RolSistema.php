<?php

namespace App\Enums;

/**
 * Alineado al modelo User (columna role).
 */
enum RolSistema: string
{
    case Admin = 'admin';
    case Operador = 'operador';
}
