<?php

namespace Database\Seeders;

use App\Models\Especie;
use App\Models\EsquemaVacuna;
use Illuminate\Database\Seeder;

class EsquemaVacunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especieCanino = Especie::where('codigo', 'CAN')->first();
        $especieFelino = Especie::where('codigo', 'FEL')->first();
        $especieEquino = Especie::where('codigo', 'EQU')->first();
        $especieConejo = Especie::where('codigo', 'CON')->first();
        $especieHuron = Especie::where('codigo', 'HUR')->first();

        $esquemas = [
            // Caninos
            ['codigo' => 'ESQ-CAN-001', 'nombre' => 'Vacuna Antirrábica Canina', 'enfermedad' => 'Rabia', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna obligatoria contra la rabia para caninos', 'especie_id' => $especieCanino?->id],
            ['codigo' => 'ESQ-CAN-002', 'nombre' => 'Parvovirus Canino', 'enfermedad' => 'Parvovirus', 'dosis' => '1 ml', 'intervalo_dias' => 21, 'descripcion' => 'Vacuna contra el parvovirus canino, se aplica en cachorros', 'especie_id' => $especieCanino?->id],
            ['codigo' => 'ESQ-CAN-003', 'nombre' => 'Moquillo Canino', 'enfermedad' => 'Distemper', 'dosis' => '1 ml', 'intervalo_dias' => 21, 'descripcion' => 'Vacuna contra el moquillo canino', 'especie_id' => $especieCanino?->id],
            ['codigo' => 'ESQ-CAN-004', 'nombre' => 'Héxuple Canina', 'enfermedad' => 'Múltiple (Distemper, Parvovirus, Hepatitis, Parainfluenza, Leptospirosis)', 'dosis' => '1 ml', 'intervalo_dias' => 21, 'descripcion' => 'Vacuna polivalente para cachorros y refuerzo anual', 'especie_id' => $especieCanino?->id],
            ['codigo' => 'ESQ-CAN-005', 'nombre' => 'Leptospirosis Canina', 'enfermedad' => 'Leptospirosis', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra la leptospirosis', 'especie_id' => $especieCanino?->id],
            ['codigo' => 'ESQ-CAN-006', 'nombre' => 'Tos de las Perreras', 'enfermedad' => 'Bordetella / Parainfluenza', 'dosis' => '0.5 ml intranasal', 'intervalo_dias' => 180, 'descripcion' => 'Vacuna contra la traqueobronquitis infecciosa canina', 'especie_id' => $especieCanino?->id],

            // Felinos
            ['codigo' => 'ESQ-FEL-001', 'nombre' => 'Vacuna Antirrábica Felina', 'enfermedad' => 'Rabia', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna obligatoria contra la rabia para felinos', 'especie_id' => $especieFelino?->id],
            ['codigo' => 'ESQ-FEL-002', 'nombre' => 'Triple Felina', 'enfermedad' => 'Panleucopenia, Rinotraqueítis, Calicivirus', 'dosis' => '1 ml', 'intervalo_dias' => 21, 'descripcion' => 'Vacuna triple contra enfermedades comunes en gatos', 'especie_id' => $especieFelino?->id],
            ['codigo' => 'ESQ-FEL-003', 'nombre' => 'Leucemia Felina', 'enfermedad' => 'Leucemia Viral Felina (FeLV)', 'dosis' => '1 ml', 'intervalo_dias' => 21, 'descripcion' => 'Vacuna contra la leucemia felina', 'especie_id' => $especieFelino?->id],

            // Equinos
            ['codigo' => 'ESQ-EQU-001', 'nombre' => 'Vacuna Antirrábica Equina', 'enfermedad' => 'Rabia', 'dosis' => '2 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra la rabia para caballos', 'especie_id' => $especieEquino?->id],
            ['codigo' => 'ESQ-EQU-002', 'nombre' => 'Influenza Equina', 'enfermedad' => 'Influenza', 'dosis' => '1 ml', 'intervalo_dias' => 180, 'descripcion' => 'Vacuna contra la influenza equina', 'especie_id' => $especieEquino?->id],
            ['codigo' => 'ESQ-EQU-003', 'nombre' => 'Tétanos Equino', 'enfermedad' => 'Tétanos', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra el tétanos en equinos', 'especie_id' => $especieEquino?->id],

            // Conejos
            ['codigo' => 'ESQ-CON-001', 'nombre' => 'Mixomatosis', 'enfermedad' => 'Mixomatosis', 'dosis' => '0.5 ml', 'intervalo_dias' => 180, 'descripcion' => 'Vacuna contra la mixomatosis en conejos', 'especie_id' => $especieConejo?->id],
            ['codigo' => 'ESQ-CON-002', 'nombre' => 'Enfermedad Hemorrágica Vírica', 'enfermedad' => 'EHV', 'dosis' => '0.5 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra la enfermedad hemorrágica vírica del conejo', 'especie_id' => $especieConejo?->id],

            // Hurones
            ['codigo' => 'ESQ-HUR-001', 'nombre' => 'Moquillo del Hurón', 'enfermedad' => 'Distemper', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra el moquillo para hurones', 'especie_id' => $especieHuron?->id],
            ['codigo' => 'ESQ-HUR-002', 'nombre' => 'Vacuna Antirrábica Hurón', 'enfermedad' => 'Rabia', 'dosis' => '1 ml', 'intervalo_dias' => 365, 'descripcion' => 'Vacuna contra la rabia para hurones', 'especie_id' => $especieHuron?->id],
        ];

        foreach ($esquemas as $esquema) {
            if ($esquema['especie_id']) {
                EsquemaVacuna::firstOrCreate(
                    ['codigo' => $esquema['codigo']],
                    $esquema
                );
            }
        }
    }
}
