<?php

use App\Models\Propietario;
use App\Models\TipoDocumento;

/*
|--------------------------------------------------------------------------
| Feature Tests — API de Propietarios
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
});

// ─── INDEX ───────────────────────────────────────────────────────

test('listar propietarios', function () {
    Propietario::factory()->count(3)->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/propietarios');

    $response->assertOk()
        ->assertJsonStructure(['data']);
});

// ─── STORE ───────────────────────────────────────────────────────

test('crear propietario con datos válidos', function () {
    $tipoDoc = TipoDocumento::first();

    $payload = [
        'tipo_doc'       => $tipoDoc->codigo,
        'nro_doc'        => 12345678,
        'nombre'         => 'Juan',
        'paterno'        => 'Perez',
        'materno'        => 'Gomez',
        'email'          => 'juan.test@example.com',
        'celular'        => '999111222',
        'nro_emergencia' => '999333444',
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/propietarios', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.nombre', 'Juan');

    $this->assertDatabaseHas('propietarios', ['nro_doc' => 12345678]);
});

test('crear propietario sin campos requeridos retorna 422', function () {
    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/propietarios', []);

    $response->assertUnprocessable();
});

// ─── SHOW ────────────────────────────────────────────────────────

test('obtener propietario por ID', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/propietarios/{$propietario->id}");

    $response->assertOk()
        ->assertJsonFragment(['nombre' => $propietario->nombre]);
});

test('obtener propietario inexistente retorna 404', function () {
    $fakeId = '00000000-0000-0000-0000-000000000000';

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/propietarios/{$fakeId}");

    $response->assertNotFound();
});

// ─── UPDATE ──────────────────────────────────────────────────────

test('actualizar propietario', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $tipoDoc = TipoDocumento::first();

    $response = $this->actingAs($this->user, 'api')
        ->putJson("/api/propietarios/{$propietario->id}", [
            'tipo_doc'  => $tipoDoc->codigo,
            'nro_doc'   => 87654321,
            'nombre'    => 'Actualizado',
            'paterno'   => $propietario->paterno,
            'email'     => $propietario->email,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('propietarios', [
        'id'     => $propietario->id,
        'nombre' => 'Actualizado',
    ]);
});

// ─── DESTROY ─────────────────────────────────────────────────────

test('eliminar propietario', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->deleteJson("/api/propietarios/{$propietario->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('propietarios', ['id' => $propietario->id]);
});

// ─── DIRECCIÓN ───────────────────────────────────────────────────

test('obtener dirección de propietario', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id'        => TipoDocumento::first()->id,
        'vivienda_direccion' => 'Av. La Molina 123',
        'vivienda_latitud'   => -12.077,
        'vivienda_longitud'  => -76.943,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/propietarios/{$propietario->id}/direccion");

    $response->assertOk()
        ->assertJsonFragment(['direccion' => 'Av. La Molina 123']);
});
