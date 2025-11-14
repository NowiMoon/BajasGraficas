<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoutesAccessTest extends TestCase
{
    use RefreshDatabase;

    public function admin_can_access_allowed_routes()
    {
        $user = User::factory()->create([
            'clave_usuario' => 322755,
            'user_type' => 1,
            'password' => bcrypt('127'),
            'status' => true,
        ]);

        $routes = [
            'register',
            'dashboard',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $status = $response->getStatusCode();

            // aceptar OK (200) o Redirect (302)
            $this->assertTrue(
                in_array($status, [200, 302]),
                "Ruta '{$route}' devolvió código inesperado: {$status}"
            );
        }
    }
}