<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Ejemplo extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'clave_usuario' => 312108,
            'user_type' => 1,
            'password' => bcrypt('12345678'),
            'status' => true,
        ]);

        User::factory()->create([
            'name' => 'karly',
            'clave_usuario' => 324278,
            'user_type' => 2,
            'password' => bcrypt('127'),
            'status' => true,
        ]);
    }
}
