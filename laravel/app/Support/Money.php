<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Utilidades mínimas para montos; el dominio de negocio vive en servicios.
 */
final class Money
{
    public static function toCents(string $amount): int
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException('Monto no numérico.');
        }

        return (int) round((float) $amount * 100);
    }
}
