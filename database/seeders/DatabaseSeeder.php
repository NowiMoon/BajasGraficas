<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Jose Luis',
            'clave_usuario' => 322754,
            'user_type' => 3,
            'password' => bcrypt('12345678'),
            'status' => true,
        ]);
    }
}
