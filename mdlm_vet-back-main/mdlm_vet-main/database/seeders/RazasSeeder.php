<?php

namespace Database\Seeders;

use App\Models\Especie;
use App\Models\Raza;
use Illuminate\Database\Seeder;

class RazasSeeder extends Seeder
{
    public function run(): void
    {
        $razas = [
            // Caninos
            ['nombre' => 'Labrador Retriever', 'codigo' => 'CAN001', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Pastor Alemán', 'codigo' => 'CAN002', 'peligroso' => true, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Golden Retriever', 'codigo' => 'CAN003', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Bulldog Francés', 'codigo' => 'CAN004', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Bulldog Inglés', 'codigo' => 'CAN005', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Poodle', 'codigo' => 'CAN006', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Beagle', 'codigo' => 'CAN007', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Rottweiler', 'codigo' => 'CAN008', 'peligroso' => true, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Yorkshire Terrier', 'codigo' => 'CAN009', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Dachshund', 'codigo' => 'CAN010', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Boxer', 'codigo' => 'CAN011', 'peligroso' => true, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Siberian Husky', 'codigo' => 'CAN012', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Chihuahua', 'codigo' => 'CAN013', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Pomerania', 'codigo' => 'CAN014', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Shih Tzu', 'codigo' => 'CAN015', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Great Dane', 'codigo' => 'CAN016', 'peligroso' => true, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Doberman Pinscher', 'codigo' => 'CAN017', 'peligroso' => true, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Australian Shepherd', 'codigo' => 'CAN018', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Cocker Spaniel', 'codigo' => 'CAN019', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Miniature Schnauzer', 'codigo' => 'CAN020', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Boston Terrier', 'codigo' => 'CAN021', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Bernese Mountain Dog', 'codigo' => 'CAN022', 'peligroso' => false, 'especie_codigo' => 'CAN'],
            ['nombre' => 'Shetland Sheepdog', 'codigo' => 'CAN023', 'peligroso' => false, 'especie_codigo' => 'CAN'],

            // Felinos
            ['nombre' => 'Persa', 'codigo' => 'FEL001', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Siamés', 'codigo' => 'FEL002', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Maine Coon', 'codigo' => 'FEL003', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Bengalí', 'codigo' => 'FEL004', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Ragdoll', 'codigo' => 'FEL005', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'British Shorthair', 'codigo' => 'FEL006', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Abisinio', 'codigo' => 'FEL007', 'peligroso' => false, 'especie_codigo' => 'FEL'],
            ['nombre' => 'Sphynx', 'codigo' => 'FEL008', 'peligroso' => false, 'especie_codigo' => 'FEL'],

            // Equinos
            ['nombre' => 'Pura Sangre', 'codigo' => 'EQU001', 'peligroso' => false, 'especie_codigo' => 'EQU'],
            ['nombre' => 'Árabe', 'codigo' => 'EQU002', 'peligroso' => false, 'especie_codigo' => 'EQU'],
            ['nombre' => 'Cuarto de Milla', 'codigo' => 'EQU003', 'peligroso' => false, 'especie_codigo' => 'EQU'],

            // Conejos
            ['nombre' => 'Holland Lop', 'codigo' => 'CON001', 'peligroso' => false, 'especie_codigo' => 'CON'],
            ['nombre' => 'Rex', 'codigo' => 'CON002', 'peligroso' => false, 'especie_codigo' => 'CON'],
            ['nombre' => 'Mini Lop', 'codigo' => 'CON003', 'peligroso' => false, 'especie_codigo' => 'CON'],

            // Hámsters
            ['nombre' => 'Sirio', 'codigo' => 'HAM001', 'peligroso' => false, 'especie_codigo' => 'HAM'],
            ['nombre' => 'Roborovski', 'codigo' => 'HAM002', 'peligroso' => false, 'especie_codigo' => 'HAM'],
            ['nombre' => 'Ruso', 'codigo' => 'HAM003', 'peligroso' => false, 'especie_codigo' => 'HAM'],
        ];

        foreach ($razas as $raza) {
            $especie = Especie::where('codigo', $raza['especie_codigo'])->first();

            Raza::create([
                'nombre' => $raza['nombre'],
                'codigo' => $raza['codigo'],
                'peligroso' => $raza['peligroso'],
                'especie_id' => $especie?->id,
            ]);
        }
    }
}
