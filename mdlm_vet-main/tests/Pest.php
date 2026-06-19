<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions — Helpers reutilizables
|--------------------------------------------------------------------------
*/

/**
 * Crea un usuario con un rol específico, lo vincula a un Personal
 * y ejecuta los seeders de catálogos para que las FK sean válidas.
 */
function crearUsuarioConRol(string $rol = 'admin'): \App\Models\User
{
    // Ejecutar seeders de catálogos base (idempotentes)
    (new \Database\Seeders\RolesAndPermissionsSeeder())->run();
    (new \Database\Seeders\TipoDocumentoSeeder())->run();
    (new \Database\Seeders\EspecieSeeder())->run();
    (new \Database\Seeders\EstadoCitaSeeder())->run();

    $user = \App\Models\User::factory()->create();
    $user->assignRole($rol);

    // Crear perfil de Personal asociado al usuario
    $tipoDoc = \App\Models\TipoDocumento::first();
    \App\Models\Personal::create([
        'user_id'     => $user->id,
        'tipo_doc_id' => $tipoDoc->id,
        'nro_doc'     => fake()->unique()->numerify('########'),
        'nombre'      => $user->name,
        'paterno'     => fake()->lastName(),
        'materno'     => fake()->lastName(),
        'email'       => $user->email,
        'celular'     => fake()->numerify('9########'),
        'especialidad'=> 'General',
        'rol_sistema' => $rol,
    ]);

    // Refrescar para cargar la relación personal
    return $user->fresh('personal');
}

/**
 * Crea los catálogos base necesarios para la mayoría de tests.
 * Llama a los seeders de razas también.
 */
function seedCatalogosBase(): void
{
    (new \Database\Seeders\RolesAndPermissionsSeeder())->run();
    (new \Database\Seeders\TipoDocumentoSeeder())->run();
    (new \Database\Seeders\EspecieSeeder())->run();
    (new \Database\Seeders\RazasSeeder())->run();
    (new \Database\Seeders\EstadoCitaSeeder())->run();
    (new \Database\Seeders\TipoExamenSeeder())->run();
}
