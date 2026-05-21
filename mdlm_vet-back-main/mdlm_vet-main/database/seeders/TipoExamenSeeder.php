<?php

namespace Database\Seeders;

use App\Models\TipoExamen;
use Illuminate\Database\Seeder;

class TipoExamenSeeder extends Seeder
{
    public function run(): void
    {
        $tipoExamen = [
            ['codigo' => 'EXA-001', 'nombre' => 'Examen de sangre', 'categoria' => 'Laboratorio', 'precio_ref' => 100.00],
            ['codigo' => 'EXA-002', 'nombre' => 'Examen de orina', 'categoria' => 'Laboratorio', 'precio_ref' => 50.00],
            ['codigo' => 'EXA-003', 'nombre' => 'Examen de heces', 'categoria' => 'Laboratorio', 'precio_ref' => 75.00],
            ['codigo' => 'EXA-004', 'nombre' => 'Examen de pelo', 'categoria' => 'Laboratorio', 'precio_ref' => 25.00],
            ['codigo' => 'EXA-005', 'nombre' => 'Examen de uña', 'categoria' => 'Laboratorio', 'precio_ref' => 10.00],
            ['codigo' => 'ECO-001', 'nombre' => 'Ecografía', 'categoria' => 'Imagenología', 'precio_ref' => 150.00],
            ['codigo' => 'RX-001', 'nombre' => 'Radiografía', 'categoria' => 'Imagenología', 'precio_ref' => 200.00],
            ['codigo' => 'RX-002', 'nombre' => 'Radiografía de tórax', 'categoria' => 'Imagenología', 'precio_ref' => 250.00],
            ['codigo' => 'RX-003', 'nombre' => 'Radiografía de abdomen', 'categoria' => 'Imagenología', 'precio_ref' => 300.00],
            ['codigo' => 'RX-004', 'nombre' => 'Radiografía de columna', 'categoria' => 'Imagenología', 'precio_ref' => 350.00],
            ['codigo' => 'RX-005', 'nombre' => 'Radiografía de extremidades', 'categoria' => 'Imagenología', 'precio_ref' => 400.00],
        ];

        foreach ($tipoExamen as $tipoExamen) {
            TipoExamen::firstOrCreate([
                'codigo' => $tipoExamen['codigo'],
                'nombre' => $tipoExamen['nombre'],
                'categoria' => $tipoExamen['categoria'],
                'precio_ref' => $tipoExamen['precio_ref'],
            ]);
        }
    }
}
