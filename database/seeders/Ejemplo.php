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
            'name' => 'karly',
            'clave_usuario' => 127,
            'user_type' => 2,
            'password' => bcrypt('127'),
            'status' => true,
        ]);
    }
}
