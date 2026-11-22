<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VehiculoSeeder::class,
            ChoferSeeder::class,
            UsuarioSeeder::class,
            IncidenteSeeder::class,
            ResponsableInvestigacionSeeder::class,
        ]);
    }
}