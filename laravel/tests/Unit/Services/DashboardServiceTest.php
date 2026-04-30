<?php

namespace Tests\Unit\Services;

use App\Models\ActivityLog;
use App\Models\Egreso;
use App\Models\Multa;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Pago;
use App\Models\Tarifa;
use App\Models\User;
use App\Services\Agua\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function estructura_del_resumen_es_estable_con_base_vacia(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $service = app(DashboardService::class);

        $resumen = $service->construirResumen($user);

        $this->assertCount(4, $resumen->tarjetasKpi);
        $this->assertSame('recaudado_mes', $resumen->tarjetasKpi[0]['key']);
        $this->assertNotEmpty($resumen->periodoReferencia);
        $this->assertSame([], $resumen->pagosRecientes);
        $this->assertSame([], $resumen->actividades);
        $this->assertArrayHasKey('al_dia', $resumen->estadoCuentas);
        $this->assertArrayHasKey('con_deuda', $resumen->estadoCuentas);
        $this->assertArrayHasKey('con_multa', $resumen->estadoCuentas);
        $this->assertArrayHasKey('usuarios_activos', $resumen->estadoCuentas);
        $this->assertTrue($resumen->esAdministrador);
    }

    #[Test]
    public function operador_marca_dashboard_sin_privilegios_de_administracion(): void
    {
        $user = User::factory()->create(['role' => 'operador']);
        $service = app(DashboardService::class);

        $resumen = $service->construirResumen($user);

        $this->assertFalse($resumen->esAdministrador);
    }

    #[Test]
    public function sin_datos_los_kpi_muestran_cero_sin_excepciones(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $service = app(DashboardService::class);

        $resumen = $service->construirResumen($user);

        $this->assertSame('0.00', collect($resumen->tarjetasKpi)->firstWhere('key', 'recaudado_mes')['value']);
        $this->assertSame('0.00', collect($resumen->tarjetasKpi)->firstWhere('key', 'deuda_pendiente')['value']);
        $this->assertSame('0', collect($resumen->tarjetasKpi)->firstWhere('key', 'padron_total')['value']);
        $this->assertSame('0.00', collect($resumen->tarjetasKpi)->firstWhere('key', 'egresos_mes')['value']);
    }

    #[Test]
    public function genera_alerta_de_padron_vacio(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $service = app(DashboardService::class);

        $resumen = $service->construirResumen($user);

        $titulos = array_column($resumen->alertas, 'titulo');
        $this->assertContains('Padrón vacío', $titulos);
    }

    #[Test]
    public function calcula_totales_del_periodo_actual(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-04-15 10:00:00'));

        $admin = User::factory()->create(['role' => 'admin']);
        $operador = User::factory()->create(['role' => 'operador']);

        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 20,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $a = PadronUsuario::query()->create([
            'codigo' => 'U-001',
            'nombre' => 'Ana',
            'apellido' => 'López',
            'direccion' => 'Av. Principal',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $b = PadronUsuario::query()->create([
            'codigo' => 'U-002',
            'nombre' => 'Luis',
            'apellido' => 'Quispe',
            'direccion' => 'Jr. Los Andes',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        Pago::query()->create([
            'padron_usuario_id' => $a->id,
            'user_id' => $operador->id,
            'periodo' => '2026-04',
            'monto_cuota' => 10,
            'monto_deuda' => 0,
            'monto_multas' => 0,
            'monto_total' => 10,
            'numero_serie' => null,
            'estado' => 'pagado',
            'pdf_path' => null,
        ]);

        Pago::query()->create([
            'padron_usuario_id' => $b->id,
            'user_id' => $operador->id,
            'periodo' => '2026-04',
            'monto_cuota' => 0,
            'monto_deuda' => 25,
            'monto_multas' => 0,
            'monto_total' => 25,
            'numero_serie' => null,
            'estado' => 'pendiente',
            'pdf_path' => null,
        ]);

        Egreso::query()->create([
            'descripcion' => 'Compra de cloro',
            'categoria' => 'insumo',
            'monto' => 5.5,
            'fecha' => '2026-04-05',
            'proveedor' => 'Proveedor X',
            'periodo' => '2026-04',
            'user_id' => $operador->id,
        ]);

        ActivityLog::query()->create([
            'user_id' => $operador->id,
            'accion' => 'Registró pago de prueba',
            'modulo' => 'Cobros',
            'ip' => '127.0.0.1',
        ]);

        $multa = Multa::query()->create([
            'nombre' => 'Retraso',
            'descripcion' => null,
            'monto' => 15,
            'activa' => true,
        ]);

        MultaUsuario::query()->create([
            'padron_usuario_id' => $a->id,
            'multa_id' => $multa->id,
            'mes' => '2026-04',
            'monto' => 15,
            'pagada' => false,
        ]);

        $service = app(DashboardService::class);
        $resumen = $service->construirResumen($admin);

        $this->assertSame('10.00', collect($resumen->tarjetasKpi)->firstWhere('key', 'recaudado_mes')['value']);
        $this->assertSame('25.00', collect($resumen->tarjetasKpi)->firstWhere('key', 'deuda_pendiente')['value']);
        $this->assertSame('2', collect($resumen->tarjetasKpi)->firstWhere('key', 'padron_total')['value']);
        $this->assertSame('5.50', collect($resumen->tarjetasKpi)->firstWhere('key', 'egresos_mes')['value']);

        $this->assertGreaterThanOrEqual(2, count($resumen->pagosRecientes));
        $this->assertNotEmpty($resumen->actividades);

        $this->assertSame(2, $resumen->estadoCuentas['usuarios_activos']);
        $this->assertSame(1, $resumen->estadoCuentas['con_deuda']);
        $this->assertSame(1, $resumen->estadoCuentas['con_multa']);
        $this->assertSame(0, $resumen->estadoCuentas['al_dia']);
    }
}
