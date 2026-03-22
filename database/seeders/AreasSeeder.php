<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    public function run()
    {
        DB::table('areas')->insert([
            ['nombre' => 'Administración'],
            ['nombre' => 'Tecnología'],
            ['nombre' => 'Recursos Humanos'],
            ['nombre' => 'Contabilidad'],
            ['nombre' => 'Ventas']
        ]);
    }
}
