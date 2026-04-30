<?php

namespace Tests\Unit\Services;

use App\Models\Multa;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Pago;
use App\Models\Tarifa;
use App\Models\User;
use App\Services\Agua\CobroService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CobroServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function resumen_incluye_estructura_esperada(): void
    {
        $operador = User::factory()->create(['role' => 'operador']);
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 18,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $padron = PadronUsuario::query()->create([
            'codigo' => 'SRV-01',
            'nombre' => 'Luis',
            'apellido' => 'Ramos',
            'direccion' => 'Jr. 1',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        Pago::query()->create([
            'padron_usuario_id' => $padron->id,
            'user_id' => $operador->id,
            'periodo' => '2026-01',
            'monto_cuota' => 10,
            'monto_deuda' => 0,
            'monto_multas' => 0,
            'monto_total' => 12,
            'estado' => 'pendiente',
        ]);

        $multa = Multa::query()->create([
            'nombre' => 'Multa prueba',
            'descripcion' => null,
            'monto' => 4,
            'activa' => true,
        ]);

        MultaUsuario::query()->create([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-02',
            'monto' => 4,
            'pagada' => false,
        ]);

        $servicio = app(CobroService::class);
        $resumen = $servicio->construirResumen($padron, '2026-03');

        $this->assertSame($padron->id, $resumen->padronId);
        $this->assertNotEmpty($resumen->lineas);
        $this->assertTrue(((float) $resumen->total) >= 34);
        $this->assertCount(1, $resumen->pagosPendientesFilas);
        $this->assertCount(1, $resumen->multasImpagasFilas);
    }
}
