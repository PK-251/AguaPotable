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

class MultaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function invitado_no_accede_a_multas(): void
    {
        $this->get(route('agua.multas.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function autenticado_ve_catalogo_vacio(): void
    {
        $this->withoutVite();

        $this->actingAs(User::factory()->create(['role' => 'operador']))
            ->get(route('agua.multas.index'))
            ->assertOk()
            ->assertSee('Catálogo de multas', false)
            ->assertSee('Sin multas en catálogo', false);
    }

    #[Test]
    public function administrador_crea_multa(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('agua.multas.store'), [
                'nombre' => 'Retraso',
                'descripcion' => 'Mes vencido',
                'monto' => '15.00',
                'activa' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('multas', [
            'nombre' => 'Retraso',
            'activa' => true,
        ]);
    }

    #[Test]
    public function validacion_falla_al_crear_multa(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->from(route('agua.multas.create'))
            ->post(route('agua.multas.store'), [
                'nombre' => '',
                'monto' => -1,
                'activa' => '1',
            ])
            ->assertSessionHasErrors(['nombre', 'monto']);
    }

    #[Test]
    public function administrador_edita_multa(): void
    {
        $multa = Multa::query()->create([
            'nombre' => 'X',
            'descripcion' => null,
            'monto' => 10,
            'activa' => true,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->put(route('agua.multas.update', $multa), [
                'nombre' => 'Y',
                'descripcion' => null,
                'monto' => 20,
                'activa' => '0',
            ])
            ->assertRedirect(route('agua.multas.show', $multa));

        $this->assertDatabaseHas('multas', [
            'id' => $multa->id,
            'nombre' => 'Y',
            'activa' => false,
        ]);
    }
}
