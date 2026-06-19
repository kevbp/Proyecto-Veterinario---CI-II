<?php

use App\Models\Animal;
use App\Models\Cita;
use App\Models\Especie;
use App\Models\EstadoCita;
use App\Models\Propietario;
use App\Models\Raza;
use App\Models\TipoDocumento;

/*
|--------------------------------------------------------------------------
| Feature Tests — API de Consultas y Citas
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
    (new \Database\Seeders\RazasSeeder())->run();

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id]);

    $this->animal = Animal::factory()->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);
});

// ─── CITAS ───────────────────────────────────────────────────────

test('crear cita con datos válidos', function () {
    $estadoCita = EstadoCita::where('codigo', 'PENDIENTE')->first()
                  ?? EstadoCita::first();

    $payload = [
        'fecha_hora'     => now()->addDays(3)->toDateTimeString(),
        'motivo'         => 'Consulta general',
        'estado_cita_id' => $estadoCita->codigo,
        'animal_id'      => $this->animal->id,
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/citas', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('citas', ['motivo' => 'Consulta general']);
});

test('listar citas', function () {
    $estadoCita = EstadoCita::first();

    Cita::create([
        'fecha_hora'     => now(),
        'motivo'         => 'Test cita',
        'estado_cita_id' => $estadoCita->id,
        'animal_id'      => $this->animal->id,
        'personal_id'    => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/citas');

    $response->assertOk();
});

// ─── CONSULTAS ───────────────────────────────────────────────────

test('crear consulta con cita existente', function () {
    $estadoCita = EstadoCita::first();

    $cita = Cita::create([
        'fecha_hora'     => now(),
        'motivo'         => 'Consulta programada',
        'estado_cita_id' => $estadoCita->id,
        'animal_id'      => $this->animal->id,
        'personal_id'    => $this->user->personal->id,
    ]);

    $payload = [
        'motivo'           => 'Vacunación de rutina',
        'diagnostico'      => 'Animal sano',
        'tratamiento'      => 'Vacuna antirrábica',
        'peso_registrado'  => 12.5,
        'observaciones'    => 'Sin observaciones',
        'cita_id'          => $cita->id,
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/consultas', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('consultas', ['motivo' => 'Vacunación de rutina']);
});

test('crear consulta sin cita genera atención inmediata', function () {
    $payload = [
        'motivo'           => 'Emergencia',
        'diagnostico'      => 'Intoxicación leve',
        'tratamiento'      => 'Lavado gástrico',
        'peso_registrado'  => 8.0,
        'observaciones'    => 'Ingirió planta tóxica',
        'animal_id'        => $this->animal->id,
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/consultas', $payload);

    $response->assertCreated();

    // Se debe haber creado una cita automática de "Atención Inmediata"
    $this->assertDatabaseHas('citas', [
        'animal_id' => $this->animal->id,
    ]);
});

test('listar consultas', function () {
    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/consultas');

    $response->assertOk();
});

test('crear consulta sin campos requeridos retorna 422', function () {
    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/consultas', []);

    $response->assertUnprocessable();
});
