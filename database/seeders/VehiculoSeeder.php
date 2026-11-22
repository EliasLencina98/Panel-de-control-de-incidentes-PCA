<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('vehiculo')->insert([
            [
                'patente' => 'AB-123-CD',
                'modelo' => 'Volvo FH 460',
                'anio' => 2021,
                'tipo_vehiculo' => 'Pesado',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'patente' => 'AC-456-EF',
                'modelo' => 'Mercedes-Benz Sprinter',
                'anio' => 2022,
                'tipo_vehiculo' => 'Transporte de personal',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }
}
