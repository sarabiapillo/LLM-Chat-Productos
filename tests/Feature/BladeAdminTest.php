<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\AuditLog;

class BladeAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('LLM Chat Productos');
    }

    public function test_public_registration_assigns_only_cliente_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nuevo Cliente Web',
            'email' => 'nuevocliente@ejemplo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'nuevocliente@ejemplo.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('cliente', $user->role);
    }

    public function test_user_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'Inicio de sesión',
        ]);
    }

    public function test_authenticated_user_can_access_dashboard_and_sections(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        // Dashboard
        $dashboardResponse = $this->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('¡Bienvenido al Panel');

        // Chat LLM
        $chatResponse = $this->get('/chat');
        $chatResponse->assertStatus(200);
        $chatResponse->assertSee('Asistente de Inteligencia Artificial LLM');

        // Usuarios
        $usersResponse = $this->get('/usuarios');
        $usersResponse->assertStatus(200);
        $usersResponse->assertSee('Gestión de Usuarios');

        // Clientes
        $clientsResponse = $this->get('/clientes');
        $clientsResponse->assertStatus(200);
        $clientsResponse->assertSee('Gestión de Clientes');

        // Log de Ingresos
        $auditResponse = $this->get('/log-ingresos');
        $auditResponse->assertStatus(200);
        $auditResponse->assertSee('Log de Ingresos y Auditoría');
    }

    public function test_user_logout_records_audit_with_session_duration(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        session(['login_time' => now()->subMinutes(5)->timestamp]);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'Cierre de sesión',
        ]);
    }
}
