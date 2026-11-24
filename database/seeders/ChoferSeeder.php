<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChoferSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();
        $faker = fake('es_AR');

        for ($i = 0; $i < 20; $i++) {
            DB::table('chofer')->insertOrIgnore([
                'dni' => (string) fake()->unique()->numberBetween(20000000, 45000000),
                'nombre' => $faker->name(),
                'fecha_nacimiento' => fake()->date('Y-m-d', '-25 years'),
                'fecha_ingreso' => fake()->date('Y-m-d', '-1 year'),
                'puesto' => 'Chofer',
                'licencia' => fake()->randomElement(['C1', 'D2', 'E1']),
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
        }
    }
}