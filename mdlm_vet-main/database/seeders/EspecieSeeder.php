<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class EspecieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especies = [
            ['codigo' => 'CAN', 'nombre' => 'Canino'],
            ['codigo' => 'FEL', 'nombre' => 'Felino'],
            ['codigo' => 'POR', 'nombre' => 'Porcino'],
            ['codigo' => 'EQU', 'nombre' => 'Equino'],
            ['codigo' => 'TOR', 'nombre' => 'Tortuga'],
            ['codigo' => 'AVE', 'nombre' => 'Ave'],
            ['codigo' => 'CON', 'nombre' => 'Conejo'],
            ['codigo' => 'HAM', 'nombre' => 'Hámster'],
            ['codigo' => 'CUY', 'nombre' => 'Cuy'],
            ['codigo' => 'PEZ', 'nombre' => 'Pez'],
            ['codigo' => 'REP', 'nombre' => 'Reptil'],
            ['codigo' => 'HUR', 'nombre' => 'Hurón'],
        ];

        foreach ($especies as $especie) {
            Especie::firstOrCreate(
                ['codigo' => $especie['codigo']],
                ['nombre' => $especie['nombre']]
            );
        }
    }
}
