<?php

namespace App\Services\Agua;

use App\Models\ActivityLog;
use App\Models\Egreso;
use App\Models\PadronUsuario;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardService
{
    public function construirResumen(User $usuario, ?Carbon $ahora = null): DashboardResumen
    {
        $ahora ??= Carbon::now();
        Carbon::setLocale('es');

        $periodo = $ahora->format('Y-m');
        $inicioMes = $ahora->copy()->startOfMonth();
        $finMes = $ahora->copy()->endOfMonth();

        $totalRecaudado = Pago::query()
            ->where('estado', 'pagado')
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->sum('monto_total');

        $totalDeudaPendiente = Pago::query()
            ->where('estado', 'pendiente')
            ->sum('monto_total');

        $totalPadron = PadronUsuario::query()->count();

        $totalEgresosMes = Egreso::query()
            ->where('periodo', $periodo)
            ->sum('monto');

        $usuariosActivos = PadronUsuario::query()->where('estado', 'activo')->count();
        $conDeuda = $this->contarActivosCon(fn (Builder $q): Builder => $q->whereHas(
            'pagos',
            fn (Builder $p): Builder => $p->where('estado', 'pendiente'),
        ));

        $conMulta = $this->contarActivosCon(fn (Builder $q): Builder => $q->whereHas(
            'multasUsuario',
            fn (Builder $m): Builder => $m->where('pagada', false),
        ));

        $alDia = PadronUsuario::query()
            ->where('estado', 'activo')
            ->whereDoesntHave('pagos', fn (Builder $p): Builder => $p->where('estado', 'pendiente'))
            ->whereDoesntHave('multasUsuario', fn (Builder $m): Builder => $m->where('pagada', false))
            ->count();

        $pagosPendientesCount = (int) Pago::query()->where('estado', 'pendiente')->count();
        $cortadosCount = (int) PadronUsuario::query()->where('estado', 'cortado')->count();

        $pagosRecientes = $this->pagosRecientes();
        $actividades = $this->actividadesRecientes();

        $tarjetasKpi = [
            [
                'key' => 'recaudado_mes',
                'label' => 'Total recaudado del mes',
                'value' => $this->formatearMontoVisual($totalRecaudado),
                'prefix' => 'S/',
                'tone' => 'success',
                'icon' => 'trending_up',
                'help' => 'Pagos registrados como pagados en '.$ahora->translatedFormat('F Y'),
            ],
            [
                'key' => 'deuda_pendiente',
                'label' => 'Deuda pendiente total',
                'value' => $this->formatearMontoVisual($totalDeudaPendiente),
                'prefix' => 'S/',
                'tone' => 'warning',
                'icon' => 'account_balance_wallet',
                'help' => 'Suma de cobros en estado pendiente',
            ],
            [
                'key' => 'padron_total',
                'label' => 'Usuarios en padrón',
                'value' => number_format($totalPadron),
                'prefix' => null,
                'tone' => 'primary',
                'icon' => 'group',
                'help' => 'Incluye activos y cortados',
            ],
            [
                'key' => 'egresos_mes',
                'label' => 'Egresos del mes',
                'value' => $this->formatearMontoVisual($totalEgresosMes),
                'prefix' => 'S/',
                'tone' => 'danger',
                'icon' => 'trending_down',
                'help' => 'Periodo contable '.$periodo,
            ],
        ];

        $alertas = $this->construirAlertas(
            (float) $totalDeudaPendiente,
            $pagosPendientesCount,
            $totalPadron,
            $cortadosCount,
        );

        return new DashboardResumen(
            periodoReferencia: $this->formatearPeriodoTitulo($periodo),
            tarjetasKpi: $tarjetasKpi,
            pagosRecientes: $pagosRecientes,
            actividades: $actividades,
            estadoCuentas: [
                'usuarios_activos' => $usuariosActivos,
                'al_dia' => $alDia,
                'con_deuda' => $conDeuda,
                'con_multa' => $conMulta,
                'nota' => '«Con deuda» y «con multa» pueden referirse al mismo usuario del padrón.',
            ],
            alertas: $alertas,
            esAdministrador: $usuario->esAdmin(),
        );
    }

    private function contarActivosCon(callable $scope): int
    {
        $query = PadronUsuario::query()->where('estado', 'activo');
        $scope($query);

        return (int) $query->count();
    }

    private function pagosRecientes(): array
    {
        return Pago::query()
            ->with([
                'padronUsuario:id,codigo,nombre,apellido',
                'user:id,name',
            ])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function (Pago $pago): array {
                $padron = $pago->padronUsuario;
                $operador = $pago->user;
                $nombreUsuario = $padron
                    ? trim($padron->nombre.' '.$padron->apellido)
                    : '—';

                return [
                    'id' => $pago->id,
                    'periodo' => $pago->periodo,
                    'monto_total' => $this->formatearMontoVisual($pago->monto_total),
                    'estado' => $pago->estado,
                    'estado_etiqueta' => $pago->estado === 'pagado' ? 'Pagado' : 'Pendiente',
                    'codigo_usuario' => $padron?->codigo ?? '—',
                    'nombre_usuario' => $nombreUsuario !== '' ? $nombreUsuario : '—',
                    'registrado_por' => $operador?->name ?? '—',
                    'fecha_humana' => $pago->created_at?->diffForHumans() ?? '—',
                ];
            })
            ->all();
    }

    private function actividadesRecientes(): array
    {
        return ActivityLog::query()
            ->with(['user:id,name'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function (ActivityLog $log): array {
                $nombre = $log->user?->name ?? 'Sistema';

                return [
                    'iniciales' => $this->inicialesDesdeNombre($nombre),
                    'tone' => $this->toneDesdeModulo((string) $log->modulo),
                    'usuario' => $nombre,
                    'accion' => $log->accion,
                    'modulo' => $log->modulo,
                    'tiempo' => $log->created_at?->diffForHumans() ?? '—',
                ];
            })
            ->all();
    }

    /**
     * @return list<array{titulo: string, mensaje: string, variante: string, icon: ?string}>
     */
    private function construirAlertas(
        float $montoDeudaPendiente,
        int $pagosPendientesCount,
        int $totalPadron,
        int $cortadosCount,
    ): array {
        $alertas = [];

        if ($totalPadron === 0) {
            $alertas[] = [
                'titulo' => 'Padrón vacío',
                'mensaje' => 'Aún no hay usuarios registrados en el padrón. Cargue residentes para habilitar cobros y reportes.',
                'variante' => 'warning',
                'icon' => 'group_off',
            ];
        }

        if ($pagosPendientesCount > 0) {
            $alertas[] = [
                'titulo' => 'Cobros pendientes por concretar',
                'mensaje' => sprintf(
                    'Hay %d registro(s) de cobro en estado pendiente por un total pendiente de S/ %s.',
                    $pagosPendientesCount,
                    $this->formatearMontoVisual((string) $montoDeudaPendiente),
                ),
                'variante' => 'info',
                'icon' => 'pending_actions',
            ];
        }

        if ($cortadosCount > 0) {
            $alertas[] = [
                'titulo' => 'Usuarios cortados',
                'mensaje' => sprintf(
                    'Existen %d usuario(s) con servicio cortado en el padrón. Revise regularizaciones.',
                    $cortadosCount,
                ),
                'variante' => 'secondary',
                'icon' => 'water_damage',
            ];
        }

        return $alertas;
    }

    private function formatearMontoVisual(float|int|string|null $valor): string
    {
        if ($valor === null || $valor === '') {
            return '0.00';
        }

        return number_format((float) $valor, 2, '.', ',');
    }

    private function formatearPeriodoTitulo(string $periodo): string
    {
        $fecha = Carbon::createFromFormat('Y-m', $periodo)->startOfMonth();

        return $fecha->translatedFormat('F Y');
    }

    private function inicialesDesdeNombre(string $nombre): string
    {
        $partes = preg_split('/\s+/u', trim($nombre), -1, PREG_SPLIT_NO_EMPTY);
        if ($partes === false || $partes === []) {
            return '—';
        }

        $slice = array_slice($partes, 0, 2);
        $salida = '';
        foreach ($slice as $parte) {
            $salida .= Str::upper(Str::substr($parte, 0, 1));
        }

        return $salida !== '' ? $salida : '—';
    }

    private function toneDesdeModulo(string $modulo): string
    {
        $paleta = ['primary', 'info', 'success', 'warning', 'danger'];
        $i = abs(crc32($modulo)) % count($paleta);

        return $paleta[$i];
    }
}
