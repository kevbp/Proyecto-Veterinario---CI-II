<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            TipoDocumentoSeeder::class,
            EspecieSeeder::class,
            EsquemaVacunaSeeder::class,
            EstadoCitaSeeder::class,
            TipoExamenSeeder::class,
            CatalogoAlergiasSeeder::class,
            CatalogoCondicionesSeeder::class,
            RazasSeeder::class,
            PropietariosAndPersonalSeeder::class,
            // Pruebas
            // VacunaSeeder::class,
            // UserSeeder::class,
            // MascotaSeeder::class,
            // ConsultaSeeder::class,
            // HorarioSeeder::class,
            // RecetaSeeder::class,
            // AnalisisSeeder::class,
            // ResultadoAnalisisSeeder::class,
            // MedicamentoSeeder::class,
            // PagoSeeder::class,
            // BoletaSeeder::class,
            // ReporteSeeder::class,
        ]);
    }
}
