<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChoferSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('chofer')->insert([
            [
                'dni' => '28456123',
                'nombre' => 'Martin Gomez',
                'fecha_nacimiento' => '1985-04-12',
                'fecha_ingreso' => '2015-03-01',
                'puesto' => 'Chofer',
                'licencia' => 'E1',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'dni' => '31987456',
                'nombre' => 'Lucia Fernandez',
                'fecha_nacimiento' => '1990-09-25',
                'fecha_ingreso' => '2020-07-15',
                'puesto' => 'Chofer',
                'licencia' => 'D2',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'dni' => '30324456',
                'nombre' => 'Pedro Fernandez',
                'fecha_nacimiento' => '1980-12-20',
                'fecha_ingreso' => '2010-07-15',
                'puesto' => 'Chofer',
                'licencia' => 'C1',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }
}