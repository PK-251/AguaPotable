<?php

namespace Tests\Feature\Web\Agua;

use App\Models\Multa;
use App\Models\MultaUsuario;
use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultaUsuarioTest extends TestCase
{
    use RefreshDatabase;

    private function crearPadron(): PadronUsuario
    {
        $tarifa = Tarifa::query()->create([
            'nombre' => 'T',
            'monto' => 5,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        return PadronUsuario::query()->create([
            'codigo' => 'MU-1',
            'nombre' => 'Pepe',
            'apellido' => 'Soto',
            'direccion' => 'Jr. 1',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);
    }

    private function multaActiva(): Multa
    {
        return Multa::query()->create([
            'nombre' => 'Mora',
            'descripcion' => null,
            'monto' => 8,
            'activa' => true,
        ]);
    }

    #[Test]
    public function invitado_no_accede_a_multas_usuario(): void
    {
        $this->get(route('agua.multas-usuario.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function asignacion_exitosa(): void
    {
        $padron = $this->crearPadron();
        $multa = $this->multaActiva();

        $this->actingAs(User::factory()->create(['role' => 'operador']))
            ->post(route('agua.multas-usuario.store'), [
                'padron_usuario_id' => $padron->id,
                'multa_id' => $multa->id,
                'mes' => '2026-04',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('multas_usuario', [
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-04',
            'pagada' => false,
        ]);
    }

    #[Test]
    public function rechaza_duplicado_mismo_mes(): void
    {
        $padron = $this->crearPadron();
        $multa = $this->multaActiva();

        MultaUsuario::query()->create([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-04',
            'monto' => 8,
            'pagada' => false,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'operador']))
            ->from(route('agua.multas-usuario.create', ['padron_usuario_id' => $padron->id]))
            ->post(route('agua.multas-usuario.store'), [
                'padron_usuario_id' => $padron->id,
                'multa_id' => $multa->id,
                'mes' => '2026-04',
            ])
            ->assertSessionHasErrors('mes');
    }

    #[Test]
    public function listado_muestra_asignacion(): void
    {
        $this->withoutVite();

        $padron = $this->crearPadron();
        $multa = $this->multaActiva();

        MultaUsuario::query()->create([
            'padron_usuario_id' => $padron->id,
            'multa_id' => $multa->id,
            'mes' => '2026-05',
            'monto' => 8,
            'pagada' => false,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('agua.multas-usuario.index'))
            ->assertOk()
            ->assertSee('MU-1', false)
            ->assertSee('Mora', false);
    }
}
