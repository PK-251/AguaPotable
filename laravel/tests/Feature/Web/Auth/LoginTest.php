<?php

namespace Tests\Feature\Web\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function muestra_el_formulario_de_login(): void
    {
        $this->withoutVite();

        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');

        $this->get(route('home'))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    #[Test]
    public function un_usuario_puede_autenticarse_con_credenciales_validas(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@jass.pe',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'admin@jass.pe',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    #[Test]
    public function operador_puede_autenticarse_con_credenciales_validas(): void
    {
        $user = User::factory()->create([
            'email' => 'operador@jass.pe',
            'password' => 'password',
            'role' => 'operador',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'operador@jass.pe',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    #[Test]
    public function usuario_autenticado_accede_al_panel_interno(): void
    {
        $this->withoutVite();

        $user = User::factory()->create([
            'password' => 'password',
            'role' => 'operador',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('admin.dashboard');
    }

    #[Test]
    public function no_autentica_con_credenciales_invalidas(): void
    {
        User::factory()->create([
            'email' => 'admin@jass.pe',
            'password' => 'password',
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'admin@jass.pe',
            'password' => 'clave-incorrecta',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    #[Test]
    public function valida_que_email_y_password_sean_obligatorios(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    #[Test]
    public function un_invitado_es_redirigido_al_login_al_acceder_a_rutas_protegidas(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('login'));

        $this->get(route('agua.cobros.index'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function la_sesion_se_regenera_despues_del_login(): void
    {
        User::factory()->create([
            'email' => 'admin@jass.pe',
            'password' => 'password',
        ]);

        $this->get(route('login'));
        $sessionIdAntes = session()->getId();

        $this->post(route('login.store'), [
            'email' => 'admin@jass.pe',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertNotSame($sessionIdAntes, session()->getId());
    }

    #[Test]
    public function un_usuario_autenticado_puede_cerrar_sesion(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
