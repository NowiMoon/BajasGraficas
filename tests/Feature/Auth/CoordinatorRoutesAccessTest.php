<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorRoutesAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function coordinator_can_access_allowed_routes()
    {
        $user = User::factory()->create([
            'clave_usuario' => 322754,
            'user_type' => 2,
            'password' => bcrypt('127'),
        ]);

        $routes = [
            'gestion_materias',
            'dashboard',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function coordinator_cannot_access_forbidden_route()
    {
        $user = User::factory()->create([
            'clave_usuario' => 322754,
            'user_type' => 2,
            'password' => bcrypt('127'),
        ]);

        $response = $this->actingAs($user)->get(route('register'));
        $response->assertStatus(403);
    }

    
}