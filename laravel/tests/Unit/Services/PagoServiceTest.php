<?php

namespace Tests\Unit\Services;

use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Models\User;
use App\Services\Agua\PagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PagoServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: PadronUsuario}
     */
    private function semilla(): array
    {
        $operador = User::factory()->create(['role' => 'operador']);
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 30,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $padron = PadronUsuario::query()->create([
            'codigo' => 'PGO-1',
            'nombre' => 'Nadia',
            'apellido' => 'Salas',
            'direccion' => 'Av. 1',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        return [$operador, $padron];
    }

    #[Test]
    public function registra_pago_pagado_con_totales_cuota(): void
    {
        [$operador, $padron] = $this->semilla();
        $servicio = app(PagoService::class);

        $pago = $servicio->registrarCobro($padron, $operador, '2026-08', 'pagado');

        $this->assertSame('pagado', $pago->estado);
        $this->assertSame('30.00', (string) $pago->monto_cuota);
        $this->assertDatabaseCount('pagos', 1);
    }

    #[Test]
    public function impide_segundo_pago_pagado_para_el_mismo_periodo(): void
    {
        [$operador, $padron] = $this->semilla();
        $servicio = app(PagoService::class);

        $servicio->registrarCobro($padron, $operador, '2026-09', 'pagado');

        $this->expectException(ValidationException::class);
        $servicio->registrarCobro($padron, $operador, '2026-09', 'pagado');
    }
}
