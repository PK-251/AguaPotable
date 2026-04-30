<?php

namespace Tests\Feature\Web\Agua;

use App\Models\PadronUsuario;
use App\Models\Tarifa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PadronTest extends TestCase
{
    use RefreshDatabase;

    private function tarifaBase(): Tarifa
    {
        return Tarifa::query()->create([
            'nombre' => 'Doméstica',
            'monto' => 15.5,
            'descripcion' => 'Prueba',
            'vigente_desde' => '2026-01-01',
        ]);
    }

    #[Test]
    public function invitado_no_puede_listar_padron(): void
    {
        $this->get(route('agua.padron.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function usuario_autenticado_ve_listado_padron(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        PadronUsuario::query()->create([
            'codigo' => 'P-001',
            'nombre' => 'Luis',
            'apellido' => 'Córdova',
            'direccion' => 'Jr. Prueba 123',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $this->actingAs($user)
            ->get(route('agua.padron.index'))
            ->assertOk()
            ->assertSee('P-001', false)
            ->assertSee('Padrón de usuarios', false);
    }

    #[Test]
    public function busqueda_por_codigo_o_nombre_filtra_resultados(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        PadronUsuario::query()->create([
            'codigo' => 'AAA-01',
            'nombre' => 'Ana',
            'apellido' => 'Prueba',
            'direccion' => 'Mz A',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        PadronUsuario::query()->create([
            'codigo' => 'BBB-02',
            'nombre' => 'Bruno',
            'apellido' => 'Demo',
            'direccion' => 'Mz B',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $this->actingAs($user)
            ->get(route('agua.padron.index', ['q' => 'AAA']))
            ->assertOk()
            ->assertSee('AAA-01', false)
            ->assertDontSee('BBB-02', false);
    }

    #[Test]
    public function crea_usuario_padron_con_datos_validos(): void
    {
        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        $payload = [
            'codigo' => 'NUEVO-01',
            'nombre' => 'Carmen',
            'apellido' => 'Ríos',
            'direccion' => 'Av. Los Andes 200',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ];

        $this->actingAs($user)
            ->post(route('agua.padron.store'), $payload)
            ->assertRedirect(route('agua.padron.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('padron_usuarios', [
            'codigo' => 'NUEVO-01',
            'nombre' => 'Carmen',
        ]);
    }

    #[Test]
    public function rechaza_creacion_con_datos_invalidos(): void
    {
        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        $this->actingAs($user)
            ->from(route('agua.padron.create'))
            ->post(route('agua.padron.store'), [
                'codigo' => '',
                'nombre' => '',
                'apellido' => '',
                'direccion' => '',
                'estado' => 'activo',
                'tarifa_id' => $tarifa->id,
            ])
            ->assertRedirect(route('agua.padron.create'))
            ->assertSessionHasErrors(['codigo', 'nombre', 'apellido', 'direccion']);
    }

    #[Test]
    public function edita_usuario_existente(): void
    {
        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        $registro = PadronUsuario::query()->create([
            'codigo' => 'EDIT-01',
            'nombre' => 'Diego',
            'apellido' => 'Luna',
            'direccion' => 'Calle Falsa 123',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $this->actingAs($user)
            ->put(route('agua.padron.update', $registro), [
                'codigo' => 'EDIT-01',
                'nombre' => 'Diego',
                'apellido' => 'Luna',
                'direccion' => 'Dirección actualizada',
                'estado' => 'cortado',
                'tarifa_id' => $tarifa->id,
            ])
            ->assertRedirect(route('agua.padron.show', $registro));

        $this->assertDatabaseHas('padron_usuarios', [
            'id' => $registro->id,
            'direccion' => 'Dirección actualizada',
            'estado' => 'cortado',
        ]);
    }

    #[Test]
    public function vista_detalle_muestra_datos(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);
        $tarifa = $this->tarifaBase();

        $registro = PadronUsuario::query()->create([
            'codigo' => 'VER-01',
            'nombre' => 'Elena',
            'apellido' => 'Vega',
            'direccion' => 'Sector 3',
            'estado' => 'activo',
            'tarifa_id' => $tarifa->id,
        ]);

        $this->actingAs($user)
            ->get(route('agua.padron.show', $registro))
            ->assertOk()
            ->assertSee('Elena', false)
            ->assertSee('VER-01', false);
    }
}
