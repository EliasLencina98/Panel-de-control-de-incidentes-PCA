<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsableInvestigacionSeeder extends Seeder
{
    public function run(): void
    {
        $incidente1 = DB::table('incidentes')->where('accion_correlativa', '')->value('id');
        $incidente2 = DB::table('incidentes')->where('accion_correlativa', '')->value('id');

        $usuario1 = DB::table('users')->where('dni', '27333444')->value('id');
        $usuario2 = DB::table('users')->where('dni', '30555666')->value('id');

        $ahora = now();
        $registros = [];

        if ($incidente1 && $usuario1) {
            $registros[] = [
                'incidente_id' => $incidente1,
                'usuario_id' => $usuario1,
                'fecha_asignacion' => '2026-08-12',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        if ($incidente1 && $usuario2) {
            $registros[] = [
                'incidente_id' => $incidente1,
                'usuario_id' => $usuario2,
                'fecha_asignacion' => '2026-08-13',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        if ($incidente2 && $usuario1) {
            $registros[] = [
                'incidente_id' => $incidente2,
                'usuario_id' => $usuario1,
                'fecha_asignacion' => '2026-09-03',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        if (!empty($registros)) {
            DB::table('responsables_de_investigacion')->insert($registros);
        }
    }
}