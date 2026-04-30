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

class TarifaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function invitado_no_accede_a_tarifas(): void
    {
        $this->get(route('agua.tarifas.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function autenticado_ve_listado_aun_vacio(): void
    {
        $this->withoutVite();

        $this->actingAs(User::factory()->create(['role' => 'operador']))
            ->get(route('agua.tarifas.index'))
            ->assertOk()
            ->assertSee('Gestión de tarifas', false)
            ->assertSee('Sin tarifas registradas', false);
    }

    #[Test]
    public function administrador_crea_tarifa_valida(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('agua.tarifas.store'), [
                'nombre' => 'Doméstica',
                'monto' => '12.50',
                'descripcion' => 'Prueba',
                'vigente_desde' => '2026-01-01',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tarifas', [
            'nombre' => 'Doméstica',
            'monto' => 12.5,
        ]);
    }

    #[Test]
    public function operador_no_puede_crear_tarifa(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'operador']))
            ->post(route('agua.tarifas.store'), [
                'nombre' => 'X',
                'monto' => 1,
                'vigente_desde' => '2026-01-01',
            ])
            ->assertForbidden();
    }

    #[Test]
    public function validacion_falla_con_datos_invalidos(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->from(route('agua.tarifas.create'))
            ->post(route('agua.tarifas.store'), [
                'nombre' => '',
                'monto' => -1,
                'vigente_desde' => '',
            ])
            ->assertSessionHasErrors(['nombre', 'monto', 'vigente_desde']);
    }

    #[Test]
    public function administrador_edita_tarifa(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tarifa = Tarifa::query()->create([
            'nombre' => 'Base',
            'monto' => 10,
            'descripcion' => null,
            'vigente_desde' => '2026-01-01',
        ]);

        $this->actingAs($admin)
            ->put(route('agua.tarifas.update', $tarifa), [
                'nombre' => 'Base+',
                'monto' => 11,
                'descripcion' => 'Act',
                'vigente_desde' => '2026-02-01',
            ])
            ->assertRedirect(route('agua.tarifas.show', $tarifa));

        $this->assertDatabaseHas('tarifas', [
            'id' => $tarifa->id,
            'nombre' => 'Base+',
            'monto' => 11,
        ]);
    }
}
