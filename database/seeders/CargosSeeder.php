<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargosSeeder extends Seeder
{
    public function run()
    {
        DB::table('cargos')->insert([

            // Administración
            ['area_id' => 1, 'nombre' => 'Gerente'],
            ['area_id' => 1, 'nombre' => 'Asistente Administrativo'],

            // Tecnología
            ['area_id' => 2, 'nombre' => 'Desarrollador'],
            ['area_id' => 2, 'nombre' => 'Soporte Técnico'],
            ['area_id' => 2, 'nombre' => 'Administrador de Sistemas'],

            // Recursos Humanos
            ['area_id' => 3, 'nombre' => 'Reclutador'],
            ['area_id' => 3, 'nombre' => 'Encargado de Nómina'],

            // Contabilidad
            ['area_id' => 4, 'nombre' => 'Contador'],
            ['area_id' => 4, 'nombre' => 'Auxiliar Contable'],

            // Ventas
            ['area_id' => 5, 'nombre' => 'Vendedor'],
            ['area_id' => 5, 'nombre' => 'Supervisor de Ventas']

        ]);
    }
}
