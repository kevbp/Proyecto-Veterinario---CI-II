<?php

namespace Database\Seeders;

use App\Models\EstadoCita;
use Illuminate\Database\Seeder;

class EstadoCitaSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Programada', 'codigo' => 'PROGRAMADA', 'color_hex' => '#3B82F6'], // Azul
            ['nombre' => 'Confirmada', 'codigo' => 'CONFIRMADA', 'color_hex' => '#10B981'], // Verde
            ['nombre' => 'En Sala de Espera', 'codigo' => 'EN_SALA', 'color_hex' => '#F59E0B'], // Naranja
            ['nombre' => 'En Consulta', 'codigo' => 'EN_CONSULTA', 'color_hex' => '#8B5CF6'], // Morado
            ['nombre' => 'Completada', 'codigo' => 'COMPLETADA', 'color_hex' => '#64748B'], // Gris
            ['nombre' => 'Cancelada', 'codigo' => 'CANCELADA', 'color_hex' => '#EF4444'], // Rojo
        ];
        foreach ($estados as $estado) {
            EstadoCita::firstOrCreate(
                ['codigo' => $estado['codigo']],
                ['nombre' => $estado['nombre'], 'color_hex' => $estado['color_hex']]
            );
        }
    }
}
