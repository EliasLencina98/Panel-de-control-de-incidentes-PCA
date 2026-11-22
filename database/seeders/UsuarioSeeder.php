<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('users')->insert([
            [
                'dni' => '27333444',
                'name' => 'Ana Sosa',
                'rol' => 'operador',
                'email' => 'ana.sosa@prueba.com', 
                'password' => Hash::make('12345678'), 
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'dni' => '30555666',
                'name' => 'Diego Perez',
                'rol' => 'administrador',
                'email' => 'diego.perez@prueba.com',
                'password' => Hash::make('12345678'),
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'dni' => '12345678',
                'name' => 'Admin prueba',
                'rol' => 'administrador',
                'email' => 'admin@prueba.com',
                'password' => Hash::make('12345678'),
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }
}