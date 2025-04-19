<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cargo::create([
            'cargo'=>'Desarrollador'
        ]);

        Cargo::create([
            'cargo'=>'Contador'
        ]);
    }
}
