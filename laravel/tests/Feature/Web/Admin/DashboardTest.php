<?php

namespace Tests\Feature\Web\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function invitado_no_puede_acceder_al_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    #[Test]
    public function usuario_autenticado_puede_ver_el_dashboard(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertViewHas('resumen');
    }

    #[Test]
    public function dashboard_renderiza_indicadores_principales(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total recaudado del mes', false)
            ->assertSee('Resumen general', false)
            ->assertSee('Estado de cuentas', false);
    }

    #[Test]
    public function operador_no_ve_accesos_exclusivos_de_administracion(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Usuarios del sistema', false);
    }

    #[Test]
    public function administrador_ve_accesos_de_administracion_en_el_dashboard(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Usuarios del sistema', false);
    }

    #[Test]
    public function operador_no_puede_acceder_a_auditoria_ni_gestion_de_usuarios(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'operador']);

        $this->actingAs($user)
            ->get(route('admin.audit.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    #[Test]
    public function administrador_puede_acceder_a_auditoria_y_usuarios(): void
    {
        $this->withoutVite();

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.audit.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk();
    }
}
