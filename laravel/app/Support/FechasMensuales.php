<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeInterface;

final class FechasMensuales
{
    public static function inicioDelMes(DateTimeInterface $referencia): DateTimeImmutable
    {
        $d = DateTimeImmutable::createFromInterface($referencia);

        return $d->modify('first day of this month')->setTime(0, 0, 0);
    }
}
