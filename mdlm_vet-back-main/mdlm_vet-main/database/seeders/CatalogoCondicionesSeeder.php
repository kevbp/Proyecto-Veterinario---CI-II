<?php

namespace Database\Seeders;

use App\Models\CatalogoCondiciones;
use Illuminate\Database\Seeder;

class CatalogoCondicionesSeeder extends Seeder
{
    public function run(): void
    {
        $condiciones = [
            ['nombre' => 'Displasia de cadera', 'codigo' => 'DISCAD'],
            ['nombre' => 'Enfermedad renal crónica', 'codigo' => 'ERENCRO'],
            ['nombre' => 'Diabetes mellitus', 'codigo' => 'DIABETES'],
            ['nombre' => 'Enfermedad hepática crónica', 'codigo' => 'ERENCRO'],
            ['nombre' => 'Enfermedad cardíaca', 'codigo' => 'ENFCAR'],
            ['nombre' => 'Enfermedad respiratoria', 'codigo' => 'ENFRES'],
            ['nombre' => 'Enfermedad gastrointestinal', 'codigo' => 'ENFGAS'],
            ['nombre' => 'Enfermedad neurológica', 'codigo' => 'ENFNEU'],
            ['nombre' => 'Enfermedad endocrina', 'codigo' => 'ENFEN'],
            ['nombre' => 'Enfermedad autoinmune', 'codigo' => 'ENFAUT'],
            ['nombre' => 'Enfermedad parasitaria', 'codigo' => 'ENFPAS'],
            ['nombre' => 'Enfermedad oncológica', 'codigo' => 'ENFONC'],
            ['nombre' => 'Enfermedad metabólica', 'codigo' => 'ENFMET'],
            ['nombre' => 'Enfermedad genética', 'codigo' => 'ENFGEN'],
            ['nombre' => 'Enfermedad congénita', 'codigo' => 'ENFCON'],
            ['nombre' => 'Enfermedad adquirida', 'codigo' => 'ENFADQ'],
            ['nombre' => 'Enfermedad traumática', 'codigo' => 'ENFTRA'],
            ['nombre' => 'Enfermedad iatrogénica', 'codigo' => 'ENFIAT'],
            ['nombre' => 'Enfermedad idiopática', 'codigo' => 'ENFID'],
        ];

        foreach ($condiciones as $condicion) {
            CatalogoCondiciones::firstOrCreate(
                ['codigo' => $condicion['codigo']],
                ['nombre' => $condicion['nombre']]
            );
        }
    }
}
