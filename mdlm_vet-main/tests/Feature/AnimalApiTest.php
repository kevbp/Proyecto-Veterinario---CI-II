<?php

use App\Models\Animal;
use App\Models\Especie;
use App\Models\Propietario;
use App\Models\Raza;
use App\Models\TipoDocumento;

/*
|--------------------------------------------------------------------------
| Feature Tests — API de Animales
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
    (new \Database\Seeders\RazasSeeder())->run();

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id]);

    $this->propietario = $propietario;
    $this->especie = $especie;
    $this->raza = $raza;
});

// ─── INDEX ───────────────────────────────────────────────────────

test('listar animales retorna paginación', function () {
    Animal::factory()->count(3)->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/animales');

    $response->assertOk()
        ->assertJsonStructure(['data', 'links']);
});

test('listar animales sin autenticación retorna 401', function () {
    $response = $this->getJson('/api/animales');

    $response->assertUnauthorized();
});

// ─── STORE ───────────────────────────────────────────────────────

test('crear animal con datos válidos', function () {
    $payload = [
        'propietario_id' => $this->propietario->id,
        'nombre'         => 'Firulais Test',
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
        'sexo'           => 'Macho',
        'color'          => 'Dorado',
        'esterilizacion' => true,
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/animales', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['nombre' => 'Firulais Test']);

    $this->assertDatabaseHas('animals', ['nombre' => 'Firulais Test']);
});

test('crear animal sin campos requeridos retorna 422', function () {
    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/animales', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['nombre']);
});

// ─── SHOW ────────────────────────────────────────────────────────

test('obtener animal existente', function () {
    $animal = Animal::factory()->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/animales/{$animal->id}");

    $response->assertOk()
        ->assertJsonFragment(['nombre' => $animal->nombre]);
});

test('obtener animal inexistente retorna 404', function () {
    $fakeId = '00000000-0000-0000-0000-000000000000';

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/animales/{$fakeId}");

    $response->assertNotFound();
});

// ─── UPDATE ──────────────────────────────────────────────────────

test('actualizar animal', function () {
    $animal = Animal::factory()->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
        'nombre'         => 'Original',
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->putJson("/api/animales/{$animal->id}", [
            'nombre'         => 'Actualizado',
            'propietario_id' => $this->propietario->id,
            'especie_id'     => $this->especie->id,
            'raza_id'        => $this->raza->id,
            'sexo'           => 'Macho',
            'color'          => 'Negro',
            'esterilizacion' => false,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('animals', [
        'id'     => $animal->id,
        'nombre' => 'Actualizado',
    ]);
});

// ─── DESTROY ─────────────────────────────────────────────────────

test('eliminar animal', function () {
    $animal = Animal::factory()->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->deleteJson("/api/animales/{$animal->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
});

// ─── FALLECIMIENTO ───────────────────────────────────────────────

test('registrar fallecimiento de animal', function () {
    $animal = Animal::factory()->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
        'fallecido'      => false,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/animales/{$animal->id}/fallecimiento");

    $response->assertOk()
        ->assertJsonFragment(['fallecido' => true]);

    $this->assertDatabaseHas('animals', [
        'id'       => $animal->id,
        'fallecido' => true,
    ]);
});

test('no puede registrar fallecimiento de animal ya fallecido', function () {
    $animal = Animal::factory()->create([
        'propietario_id'      => $this->propietario->id,
        'especie_id'          => $this->especie->id,
        'raza_id'             => $this->raza->id,
        'fallecido'           => true,
        'fecha_fallecimiento' => now()->format('Y-m-d'),
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/animales/{$animal->id}/fallecimiento");

    $response->assertStatus(409);
});

// ─── GET PROPIETARIO ─────────────────────────────────────────────

test('obtener propietario del animal', function () {
    $animal = Animal::factory()->create([
        'propietario_id' => $this->propietario->id,
        'especie_id'     => $this->especie->id,
        'raza_id'        => $this->raza->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/animales/{$animal->id}/propietario");

    $response->assertOk()
        ->assertJsonFragment(['nombre' => $this->propietario->nombre]);
});
