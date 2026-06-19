<?php

namespace Database\Seeders;

use App\Models\CatalogoAlergias;
use Illuminate\Database\Seeder;

class CatalogoAlergiasSeeder extends Seeder
{
    public function run(): void
    {
        $alergias = [
            ['nombre' => 'Pollo', 'categoria' => 'Alimenticia', 'codigo' => 'POLLO'],
            ['nombre' => 'Res', 'categoria' => 'Alimenticia', 'codigo' => 'RES'],
            ['nombre' => 'Cerdo', 'categoria' => 'Alimenticia', 'codigo' => 'CERDO'],
            ['nombre' => 'Pescado', 'categoria' => 'Alimenticia', 'codigo' => 'PESCADO'],
            ['nombre' => 'Lacteos', 'categoria' => 'Alimenticia', 'codigo' => 'LACTEOS'],
            ['nombre' => 'Trigo', 'categoria' => 'Alimenticia', 'codigo' => 'TRIGO'],
            ['nombre' => 'Maiz', 'categoria' => 'Alimenticia', 'codigo' => 'MAIZ'],
            ['nombre' => 'Soja', 'categoria' => 'Alimenticia', 'codigo' => 'SOJA'],
            ['nombre' => 'Huevo', 'categoria' => 'Alimenticia', 'codigo' => 'HUEVO'],
            ['nombre' => 'Alergia a la penicilina', 'categoria' => 'Medicamento', 'codigo' => 'AL-001'],
            ['nombre' => 'Alergia a la amoxicilina', 'categoria' => 'Medicamento', 'codigo' => 'AL-002'],
            ['nombre' => 'Alergia a la aspirina', 'categoria' => 'Medicamento', 'codigo' => 'AL-003'],
            ['nombre' => 'Alergia a la ibuprofeno', 'categoria' => 'Medicamento', 'codigo' => 'AL-004'],
            ['nombre' => 'Alergia a la naproxeno', 'categoria' => 'Medicamento', 'codigo' => 'AL-005'],
            ['nombre' => 'Alergia a la codeina', 'categoria' => 'Medicamento', 'codigo' => 'AL-006'],
            ['nombre' => 'Alergia a la morfina', 'categoria' => 'Medicamento', 'codigo' => 'AL-007'],
        ];

        foreach ($alergias as $alergia) {
            CatalogoAlergias::firstOrCreate(
                ['codigo' => $alergia['codigo']],
                ['nombre' => $alergia['nombre'], 'categoria' => $alergia['categoria']]
            );
        }
    }
}
