<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['codigo' => 'DNI', 'nombre' => 'Documento Nacional de Identidad'],
            ['codigo' => 'CE', 'nombre' => 'Carnet de Extranjería'],
            ['codigo' => 'RUC', 'nombre' => 'Registro Único de Contribuyentes'],
            ['codigo' => 'PAS', 'nombre' => 'Pasaporte'],
            ['codigo' => 'PTP', 'nombre' => 'Permiso Temporal de Permanencia'],
        ];

        foreach ($tipos as $tipo) {
            TipoDocumento::firstOrCreate(
                ['codigo' => $tipo['codigo']],
                ['nombre' => $tipo['nombre']]
            );
        }
    }
}
