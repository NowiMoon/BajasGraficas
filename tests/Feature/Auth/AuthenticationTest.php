<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // crear usuario con contraseña conocida y status activo
        $user = User::factory()->create([
            'clave_usuario' => '12345678',
            'password' => bcrypt('password'),
            'status' => true,
        ]);

        $response = $this->post('/login', [
            'clave_usuario' => $user->clave_usuario,
            'password' => 'password',
        ]);

        // si no se autenticó, guardar información útil para depurar
        if (! $this->isAuthenticated()) {
            $info = "LOGIN TEST FAILED\n";
            $info .= "Response status: " . $response->getStatusCode() . "\n";
            $info .= "Response content:\n" . $response->getContent() . "\n\n";
            $info .= "Session errors:\n";
            $session = $response->getSession();
            if ($session) {
                $errors = $session->get('errors');
                $info .= print_r($errors ? $errors->getBag('default')->getMessages() : [], true);
            }
            File::append(storage_path('logs/test_auth_debug.log'), $info);
        }

        // afirmar que se autenticó como el usuario creado
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'clave_usuario' => '87654321',
            'password' => bcrypt('password'),
            'status' => true,
        ]);

        $this->post('/login', [
            'clave_usuario' => $user->clave_usuario,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
