<?php

use App\Models\Animal;
use App\Models\Especie;
use App\Models\Propietario;
use App\Models\Raza;
use App\Models\TipoDocumento;

/*
|--------------------------------------------------------------------------
| Feature Tests — Autorización por Roles
|--------------------------------------------------------------------------
*/

// ─── USUARIO NO AUTENTICADO ──────────────────────────────────────

test('usuario no autenticado recibe 401 en rutas protegidas', function () {
    $this->getJson('/api/animales')->assertUnauthorized();
    $this->getJson('/api/propietarios')->assertUnauthorized();
    $this->getJson('/api/campanias')->assertUnauthorized();
    $this->getJson('/api/consultas')->assertUnauthorized();
    $this->getJson('/api/citas')->assertUnauthorized();
    $this->getJson('/api/medicamentos')->assertUnauthorized();
});

// ─── VETERINARIO ─────────────────────────────────────────────────

test('veterinario puede ver consultas', function () {
    $vet = crearUsuarioConRol('veterinario');

    $response = $this->actingAs($vet, 'api')
        ->getJson('/api/consultas');

    $response->assertOk();
});

test('veterinario puede crear consultas', function () {
    $vet = crearUsuarioConRol('veterinario');
    (new \Database\Seeders\RazasSeeder())->run();

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id]);

    $animal = Animal::factory()->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    $payload = [
        'motivo'          => 'Control rutinario',
        'diagnostico'     => 'Sano',
        'tratamiento'     => 'Ninguno',
        'peso_registrado' => 10.0,
        'observaciones'   => 'Ninguna',
        'animal_id'       => $animal->id,
    ];

    $response = $this->actingAs($vet, 'api')
        ->postJson('/api/consultas', $payload);

    $response->assertCreated();
});

test('veterinario no puede crear campañas', function () {
    $vet = crearUsuarioConRol('veterinario');

    $payload = [
        'nombre'            => 'Campaña Test',
        'lugar'             => 'Lugar',
        'fecha_hora_inicio' => now()->addDays(5)->toDateTimeString(),
        'fecha_hora_fin'    => now()->addDays(12)->toDateTimeString(),
        'responsable_id'    => $vet->personal->id,
    ];

    $response = $this->actingAs($vet, 'api')
        ->postJson('/api/campanias', $payload);

    $response->assertForbidden();
});

test('veterinario no puede eliminar usuarios', function () {
    $vet = crearUsuarioConRol('veterinario');
    $admin = crearUsuarioConRol('admin');

    $response = $this->actingAs($vet, 'api')
        ->deleteJson("/api/usuarios/{$admin->id}");

    $response->assertForbidden();
});

// ─── RECEPCIONISTA ───────────────────────────────────────────────

test('recepcionista puede ver citas', function () {
    $recep = crearUsuarioConRol('recepcionista');

    $response = $this->actingAs($recep, 'api')
        ->getJson('/api/citas');

    $response->assertOk();
});

test('recepcionista puede crear citas', function () {
    $recep = crearUsuarioConRol('recepcionista');
    (new \Database\Seeders\RazasSeeder())->run();

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id]);

    $animal = Animal::factory()->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    $estadoCita = \App\Models\EstadoCita::first();

    $payload = [
        'fecha_hora'     => now()->addDays(2)->toDateTimeString(),
        'motivo'         => 'Consulta de rutina',
        'estado_cita_id' => $estadoCita->codigo,
        'animal_id'      => $animal->id,
    ];

    $response = $this->actingAs($recep, 'api')
        ->postJson('/api/citas', $payload);

    $response->assertCreated();
});

test('recepcionista no puede crear consultas', function () {
    $recep = crearUsuarioConRol('recepcionista');

    $response = $this->actingAs($recep, 'api')
        ->postJson('/api/consultas', [
            'motivo' => 'Test',
        ]);

    $response->assertForbidden();
});

// ─── PROPIETARIO ─────────────────────────────────────────────────

test('propietario puede ver mascotas', function () {
    $prop = crearUsuarioConRol('propietario');

    $response = $this->actingAs($prop, 'api')
        ->getJson('/api/animales');

    $response->assertOk();
});

test('propietario no puede crear propietarios', function () {
    $prop = crearUsuarioConRol('propietario');

    $response = $this->actingAs($prop, 'api')
        ->postJson('/api/propietarios', [
            'tipo_doc'  => 'DNI',
            'nro_doc'   => 99999999,
            'nombre'    => 'No Autorizado',
            'paterno'   => 'Test',
            'email'     => 'no@test.com',
        ]);

    $response->assertForbidden();
});

test('propietario no puede eliminar animales', function () {
    $prop = crearUsuarioConRol('propietario');
    (new \Database\Seeders\RazasSeeder())->run();

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id]);

    $animal = Animal::factory()->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    $response = $this->actingAs($prop, 'api')
        ->deleteJson("/api/animales/{$animal->id}");

    $response->assertForbidden();
});

// ─── ADMIN ───────────────────────────────────────────────────────

test('admin tiene acceso completo a todos los módulos', function () {
    $admin = crearUsuarioConRol('admin');

    $this->actingAs($admin, 'api')->getJson('/api/animales')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/propietarios')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/campanias')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/consultas')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/citas')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/medicamentos')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/instrumentos')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/personal')->assertOk();
    $this->actingAs($admin, 'api')->getJson('/api/usuarios')->assertOk();
});
