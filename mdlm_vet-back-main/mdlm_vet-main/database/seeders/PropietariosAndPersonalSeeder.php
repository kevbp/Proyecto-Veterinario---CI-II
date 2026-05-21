<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personal;
use App\Models\Propietario;
use App\Models\Medicamento;
use App\Models\TipoDocumento;

class PropietariosAndPersonalSeeder extends Seeder
{
    public function run(): void
    {
        $ruc = TipoDocumento::where('codigo', 'RUC')->firstOrFail();
        $dni = TipoDocumento::where('codigo', 'DNI')->firstOrFail();

        // USUARIO ADMINISTRADOR PRINCIPAL
        $admin = User::firstOrCreate(
            ['email' => 'admin@veterinaria.com'],
            [
                'name' => 'Administrador Principal',
                'password' => bcrypt('password'), // Asegúrate de cambiar esto en producción
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // ALBERGUE MUNICIPAL DE LA MOLINA
        Propietario::firstOrCreate(
            [
                'email' => 'veterinaria@munimolina.gob.pe'
            ],
            [
                'tipo_doc_id' => $ruc->id,
                'nro_doc' => '20131374731',
                'nombre' => 'Albergue Municipal de La Molina',
                'paterno' => '',
                'celular' => '983221201',
                'nro_emergencia' => '7544000',
                'invitation_token' => null,
                'invitation_sent_at' => null,
                'invitation_accepted_at' => now(),
            ]
        );
        
        // PERSONAL VETERINARIO
        $user = User::firstOrCreate(
            ['email' => 'veterinario@veterinaria.com'],
            [
                'name' => 'Veterinario General',
                'password' => bcrypt('password'), // Asegúrate de cambiar esto en producción
                'email_verified_at' => now(),
            ]
        )->assignRole('veterinario');

        Personal::firstOrCreate(
            [
                'email' => 'veterinario@veterinaria.com'
            ],
            [
                'user_id' => $user->id,
                'tipo_doc_id' => $dni->id,
                'nro_doc' => '00000000',
                'nombre' => 'Veterinario',
                'paterno' => 'General',
                'celular' => '999999999',
                'rol_sistema' => 'veterinario',
            ]
        );

        // MEDICAMENTO DE TESTEO
        Medicamento::firstOrCreate(
            ['codigo' => 'MED001'],
            [
                'nombre' => 'Medicamento de Testeo',
                'stock' => 100,
                'estado' => 'activo',
            ]
        );
    }
}
