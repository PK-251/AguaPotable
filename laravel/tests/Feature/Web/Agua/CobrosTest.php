<?php

namespace Tests\Feature\Web\Agua;

use App\Models\Multa;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Pago;
use App\Models\Tarifa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CobrosTest extends TestCase
{
    use RefreshDatabase;

    private function prepararAccion(): PadronUsuario
    {
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 20,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        return PadronUsuario::query()->create([
            'codigo' => 'COB-01',
            'nombre' => 'Mario',
            'apellido' => 'Vargas',
            'direccion' => 'Jr. Cobros',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);
    }

    #[Test]
    public function invitado_no_puede_acceder_a_cobros(): void
    {
        $this->get(route('agua.cobros.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function usuario_autenticado_accede_a_cobros(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);

        $this->actingAs($user)
            ->get(route('agua.cobros.index'))
            ->assertOk()
            ->assertSee('Gestión de cobros', false);
    }

    #[Test]
    public function busqueda_muestra_coincidencias(): void
    {
        $this->withoutVite();

        $usuario = User::factory()->create(['role' => 'operador']);
        $padron = $this->prepararAccion();

        $this->actingAs($usuario)
            ->get(route('agua.cobros.index', ['q' => 'COB-01']))
            ->assertOk()
            ->assertSee('Mario', false)
            ->assertSee('COB-01', false);
    }

    #[Test]
    public function resumen_muestra_total_esperado(): void
    {
        $this->withoutVite();

        $usuario = User::factory()->create(['role' => 'operador']);
        $padron = $this->prepararAccion();
        $periodo = '2026-04';

        Pago::query()->create([
            'padron_usuario_id' => $padron->id,
            'user_id' => $usuario->id,
            'periodo' => '2026-01',
            'monto_cuota' => 10,
            'monto_deuda' => 0,
            'monto_multas' => 0,
            'monto_total' => 25,
            'estado' => 'pendiente',
        ]);

        $multa = Multa::query()->create([
            'nombre' => 'Atraso',
            'descripcion' => null,
            'monto' => 5,
            'activa' => true,
        ]);

        MultaUsuario::query()->create([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => $periodo,
            'monto' => 5,
            'pagada' => false,
        ]);

        $this->actingAs($usuario)
            ->get(route('agua.cobros.index', ['padron' => $padron->id, 'periodo' => $periodo]))
            ->assertOk()
            ->assertSee('Detalle de deuda', false)
            ->assertSee('50.00', false);
    }

    #[Test]
    public function registra_pago_pagado_y_marca_multas(): void
    {
        $usuarioSys = User::factory()->create(['role' => 'operador']);
        $padron = $this->prepararAccion();
        $periodo = '2026-05';

        $multa = Multa::query()->create([
            'nombre' => 'Atraso',
            'descripcion' => null,
            'monto' => 3,
            'activa' => true,
        ]);

        $asignacion = MultaUsuario::query()->create([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => $periodo,
            'monto' => 3,
            'pagada' => false,
        ]);

        $this->actingAs($usuarioSys)
            ->post(route('agua.cobros.store'), [
                'padron_usuario_id' => $padron->id,
                'periodo' => $periodo,
                'estado' => 'pagado',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pagos', [
            'padron_usuario_id' => $padron->id,
            'periodo' => $periodo,
            'estado' => 'pagado',
        ]);

        $this->assertDatabaseHas('multas_usuario', [
            'id' => $asignacion->id,
            'pagada' => true,
        ]);
    }

    #[Test]
    public function rechaza_pago_invalido(): void
    {
        $usuarioSys = User::factory()->create(['role' => 'operador']);
        $padron = $this->prepararAccion();

        $this->actingAs($usuarioSys)
            ->from(route('agua.cobros.index', ['padron' => $padron->id]))
            ->post(route('agua.cobros.store'), [
                'padron_usuario_id' => $padron->id,
                'periodo' => '2026-13',
                'estado' => 'pagado',
            ])
            ->assertSessionHasErrors('periodo');

        $this->assertDatabaseCount('pagos', 0);
    }

    #[Test]
    public function historial_renderiza_ultimos_pagos(): void
    {
        $this->withoutVite();

        $usuarioSys = User::factory()->create(['role' => 'operador']);
        $padron = $this->prepararAccion();

        Pago::query()->create([
            'padron_usuario_id' => $padron->id,
            'user_id' => $usuarioSys->id,
            'periodo' => '2026-06',
            'monto_cuota' => 20,
            'monto_deuda' => '0.00',
            'monto_multas' => '0.00',
            'monto_total' => 20,
            'estado' => 'pagado',
        ]);

        $this->actingAs($usuarioSys)
            ->get(route('agua.cobros.index'))
            ->assertOk()
            ->assertSee('Últimos cobros', false)
            ->assertSee($padron->codigo, false);
    }

    #[Test]
    public function usuario_inexistente_muestra_mensaje(): void
    {
        $this->withoutVite();

        $usuarioSys = User::factory()->create(['role' => 'operador']);

        $this->actingAs($usuarioSys)
            ->get(route('agua.cobros.index', ['padron' => 99999]))
            ->assertOk()
            ->assertSee('no está disponible', false);
    }
}
