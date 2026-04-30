<?php

namespace App\Services\Agua;

/**
 * Vista de datos del panel principal (dashboard interno).
 *
 * @phpstan-type TarjetaKpi array{
 *     key: string,
 *     label: string,
 *     value: string,
 *     prefix: ?string,
 *     tone: ?string,
 *     icon: ?string,
 *     help: ?string
 * }
 * @phpstan-type FilaPagoReciente array{
 *     id: int,
 *     periodo: string,
 *     monto_total: string,
 *     estado: string,
 *     estado_etiqueta: string,
 *     codigo_usuario: string,
 *     nombre_usuario: string,
 *     registrado_por: string,
 *     fecha_humana: string
 * }
 * @phpstan-type FilaActividad array{
 *     iniciales: string,
 *     tone: string,
 *     usuario: string,
 *     accion: string,
 *     modulo: string,
 *     tiempo: string
 * }
 * @phpstan-type AlertaOperativa array{titulo: string, mensaje: string, variante: string, icon: ?string}
 */
final readonly class DashboardResumen
{
    /**
     * @param  list<TarjetaKpi>  $tarjetasKpi
     * @param  list<FilaPagoReciente>  $pagosRecientes
     * @param  list<FilaActividad>  $actividades
     * @param  array{
     *     usuarios_activos: int,
     *     al_dia: int,
     *     con_deuda: int,
     *     con_multa: int,
     *     nota: string
     * }  $estadoCuentas
     * @param  list<AlertaOperativa>  $alertas
     */
    public function __construct(
        public string $periodoReferencia,
        public array $tarjetasKpi,
        public array $pagosRecientes,
        public array $actividades,
        public array $estadoCuentas,
        public array $alertas,
        public bool $esAdministrador,
    ) {}
}
