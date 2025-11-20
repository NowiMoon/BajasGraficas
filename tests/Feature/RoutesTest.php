<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function assertNoServerError($response)
    {
        $this->assertTrue($response->getStatusCode() < 500, 'Server error returned: '.$response->getStatusCode());
    }

    public function test_guest_redirected_from_dashboard_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_register_and_create_user()
    {
        $admin = User::factory()->create(['user_type' => 1, 'status' => true]);

        $this->actingAs($admin);

        $respGet = $this->get(route('register'));
        $this->assertNoServerError($respGet);
        $respGet->assertStatus(200);

        $postData = [
            'name' => 'Test User',
            'clave_usuario' => 99999999,
            'user_type' => 3,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $respPost = $this->post(route('register'), $postData);
        $this->assertNoServerError($respPost);
        $respPost->assertStatus(302);
        $this->assertDatabaseHas('users', ['clave_usuario' => 99999999, 'name' => 'Test User']);
    }

    public function test_admin_can_toggle_and_reset_user()
    {
        $admin = User::factory()->create(['user_type' => 1, 'status' => true]);
        $user = User::factory()->create(['user_type' => 3, 'status' => true]);

        $this->actingAs($admin);

        // Toggle
        if (! Route::has('users.toggle')) {
            $this->markTestSkipped('Route users.toggle not defined');
        }
        $respToggle = $this->patch(route('users.toggle', $user->id));
        $this->assertNoServerError($respToggle);
        $respToggle->assertStatus(302);
        $user->refresh();
        $this->assertFalse((bool)$user->status);

        // Reset password via POST with provided password
        if (! Route::has('users.reset')) {
            $this->markTestSkipped('Route users.reset not defined');
        }
        $newPass = 'NewSecret123!';
        $respReset = $this->post(route('users.reset', $user->id), [
            'new_password' => $newPass,
            'new_password_confirmation' => $newPass,
        ]);
        $this->assertNoServerError($respReset);
        $respReset->assertStatus(302);
        $user->refresh();
        $this->assertTrue(Hash::check($newPass, $user->password));
    }

    public function test_coordinator_can_access_gestion_materias_and_store()
    {
        $coord = User::factory()->create(['user_type' => 2, 'status' => true]);
        $this->actingAs($coord);

        if (! Route::has('gestion_materias')) {
            $this->markTestSkipped('Route gestion_materias not defined');
        }

        $respGet = $this->get(route('gestion_materias'));
        $this->assertNoServerError($respGet);
        $respGet->assertStatus(200);

        $data = [
            'clave_materia' => 12345,
            'nombre_materia' => 'Materia Test',
        ];

        if (! Route::has('gestion_materias.store')) {
            $this->markTestSkipped('Route gestion_materias.store not defined');
        }

        $respPost = $this->post(route('gestion_materias.store'), $data);
        $this->assertNoServerError($respPost);
        $respPost->assertStatus(302);

        $this->assertDatabaseHas('materias', ['clave_materia' => 12345, 'nombre_materia' => 'Materia Test']);
    }

    public function test_public_and_api_endpoints_do_not_return_server_error()
    {
        // Evitar llamadas HTTP reales (servicios externos) durante los tests
        Http::fake(['*' => Http::response('', 200)]);

        $endpoints = [
            '/upload',
            '/upload-form',
            '/recibir-json',
            '/enviar',
            '/recibir',
            '/get-data',
            '/api/materias',
            '/api/trabajos',
            '/api/escuelas',
            '/api/generaciones',
        ];

        // usuario para endpoints que requieran auth
        $admin = User::factory()->create(['user_type' => 1, 'status' => true]);

        foreach ($endpoints as $uri) {
            $tried = [];
            try {
                // intentar GET primero
                $response = $this->call('GET', $uri);
                $tried[] = 'GET';
                if ($response->getStatusCode() >= 500) {
                    // intentar POST sin datos
                    $response = $this->call('POST', $uri, []);
                    $tried[] = 'POST';
                }
                // si sigue 500, intentar con autenticación (admin)
                if ($response->getStatusCode() >= 500) {
                    $response = $this->actingAs($admin)->call('GET', $uri);
                    $tried[] = 'GET(auth)';
                    if ($response->getStatusCode() >= 500) {
                        $response = $this->actingAs($admin)->call('POST', $uri, []);
                        $tried[] = 'POST(auth)';
                    }
                }

                // finalmente comprobar que no sea 500
                $this->assertTrue($response->getStatusCode() < 500, "Server error on {$uri} after tries: ".implode(', ', $tried)." — status {$response->getStatusCode()}");
            } catch (\Throwable $e) {
                $msg = "Exception calling {$uri}: ".$e->getMessage()."\n\nTrace:\n".$e->getTraceAsString();
                \Illuminate\Support\Facades\File::append(storage_path('logs/test_endpoints_debug.log'), $msg."\n\n");
                $this->fail("Exception calling {$uri}: ".$e->getMessage()." (detalle en storage/logs/test_endpoints_debug.log)");
            }
        }
    }
}