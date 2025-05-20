<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Coordinador',
            'clave_usuario' => 324278,
            'user_type' => 2,
            'password' => bcrypt('127'),
            'status' => true,
        ]);

        User::factory()->create([
            'name' => 'Trabajador',
            'clave_usuario' => 12345678,
            'user_type' => 3,
            'password' => bcrypt('127'),
            'status' => true,
        ]);
    }
}
