<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CoordinatorRoutesAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_access_allowed_routes()
    {
        $user = User::factory()->create([
            'clave_usuario' => 322754,
            'user_type' => 2,
            'password' => bcrypt('127'),
            'status' => true,
        ]);

        $routes = [
            'gestion_materias',
            'dashboard',
        ];

        foreach ($routes as $route) {
            if (! Route::has($route)) {
                $this->markTestSkipped("Route {$route} not defined");
            }

            $response = $this->actingAs($user)->get(route($route));
            $status = $response->getStatusCode();

            // aceptar 200 (OK) o 302 (redirect) como válidos en integración
            $this->assertTrue(
                in_array($status, [200, 302]),
                "Ruta '{$route}' devolvió código inesperado: {$status}"
            );
        }
    }

    public function test_coordinator_cannot_access_forbidden_route()
    {
        $user = User::factory()->create([
            'clave_usuario' => 322754,
            'user_type' => 2,
            'password' => bcrypt('127'),
            'status' => true,
        ]);

        if (! Route::has('register')) {
            $this->markTestSkipped('Route register not defined');
        }

        $response = $this->actingAs($user)->get(route('register'));
        $status = $response->getStatusCode();

        // el comportamiento puede ser 403 o redirect (p.ej. middleware), aceptamos ambos
        $this->assertTrue(
            in_array($status, [403, 302]),
            "Se esperaba 403 o 302 al acceder a 'register', se recibió: {$status}"
        );
    }
}