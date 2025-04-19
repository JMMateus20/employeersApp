<?php

namespace Database\Seeders;

use App\Models\Dia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableDiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dia::create([
            'dia'=>'Lunes'
        ]);

        Dia::create([
            'dia'=>'Martes'
        ]);

        Dia::create([
            'dia'=>'Miercoles'
        ]);

        Dia::create([
            'dia'=>'Jueves'
        ]);

        Dia::create([
            'dia'=>'Viernes'
        ]);

        Dia::create([
            'dia'=>'Sabado'
        ]);
    }
}
