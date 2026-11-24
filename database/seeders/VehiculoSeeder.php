<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();
        $modelos = [
            'Scania R 450',
            'Iveco Stralis',
            'Ford Cargo',
            'Toyota Hilux',
            'Renault Master',
            'Volvo FH 460',
            'Mercedes-Benz Sprinter',
        ];

        $tipos = [
            'Pesado',
            'Liviano',
            'Transporte de personal'
        ];

        $registros = [];

        for ($i = 0; $i < 15; $i++) {
            $registros[] = [
                'patente' => strtoupper(fake()->unique()->bothify('A?-###-??')),
                'modelo' => fake()->randomElement($modelos),
                'anio' => fake()->numberBetween(2016, 2026),
                'tipo_vehiculo' => fake()->randomElement($tipos),
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        DB::table('vehiculo')->insertOrIgnore($registros);
    
    }
}
