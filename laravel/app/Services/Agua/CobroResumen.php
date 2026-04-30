<?php

namespace App\Services\Agua;

/**
 * Vista agregada para la pantalla de cobros.
 *
 * @param  list<array{concepto: string, monto: string, tone: ?string, badge: ?string}>  $lineas
 * @param  list<array<string, mixed>>  $pagosPendientesFilas
 * @param  list<array<string, mixed>>  $multasImpagasFilas
 */
final readonly class CobroResumen
{
    public function __construct(
        public int $padronId,
        public string $codigo,
        public string $nombreCompleto,
        public string $direccion,
        public string $estado,
        public ?string $tarifaNombre,
        public ?string $tarifaMonto,
        public string $periodo,
        public array $lineas,
        public string $total,
        public array $pagosPendientesFilas,
        public array $multasImpagasFilas,
        public bool $existePagadoParaPeriodo,
        public ?int $pagoPeriodoId,
        public ?string $pagoPeriodoEstado,
    ) {}
}
