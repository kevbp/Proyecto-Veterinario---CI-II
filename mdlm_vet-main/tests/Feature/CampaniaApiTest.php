<?php

use App\Enums\EstadoCampania;
use App\Models\Campania;

/*
|--------------------------------------------------------------------------
| Feature Tests — API de Campañas (Ciclo de Vida)
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
});

// ─── CRUD ────────────────────────────────────────────────────────

test('listar campañas', function () {
    Campania::factory()->count(2)->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/campanias');

    $response->assertOk()
        ->assertJsonStructure(['data']);
});

test('crear campaña con datos válidos', function () {
    $payload = [
        'nombre'            => 'Campaña de Vacunación 2026',
        'descripcion'       => 'Vacunación masiva contra la rabia',
        'lugar'             => 'Parque Municipal',
        'fecha_hora_inicio' => now()->addDays(5)->toDateTimeString(),
        'fecha_hora_fin'    => now()->addDays(12)->toDateTimeString(),
        'responsable_id'    => $this->user->personal->id,
    ];

    $response = $this->actingAs($this->user, 'api')
        ->postJson('/api/campanias', $payload);

    $response->assertCreated();

    $this->assertDatabaseHas('campanias', ['nombre' => 'Campaña de Vacunación 2026']);
});

test('obtener campaña por ID', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/campanias/{$campania->id}");

    $response->assertOk()
        ->assertJsonFragment(['nombre' => $campania->nombre]);
});

test('actualizar campaña planificada', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
        'estado'         => EstadoCampania::PLANIFICADA,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->putJson("/api/campanias/{$campania->id}", [
            'nombre'            => 'Nombre Actualizado',
            'descripcion'       => 'Descripción nueva',
            'lugar'             => 'Nuevo Lugar',
            'fecha_hora_inicio' => $campania->fecha_hora_inicio->toDateTimeString(),
            'fecha_hora_fin'    => $campania->fecha_hora_fin->toDateTimeString(),
            'responsable_id'    => $this->user->personal->id,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('campanias', [
        'id'     => $campania->id,
        'nombre' => 'Nombre Actualizado',
    ]);
});

test('eliminar campaña planificada', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
        'estado'         => EstadoCampania::PLANIFICADA,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->deleteJson("/api/campanias/{$campania->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('campanias', ['id' => $campania->id]);
});

// ─── CICLO DE VIDA ───────────────────────────────────────────────

test('iniciar campaña planificada', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
        'estado'         => EstadoCampania::PLANIFICADA,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/campanias/{$campania->id}/iniciar");

    $response->assertOk();

    $this->assertDatabaseHas('campanias', [
        'id'     => $campania->id,
        'estado' => EstadoCampania::EN_CURSO->value,
    ]);
});

test('no puede iniciar campaña que no está planificada', function () {
    $campania = Campania::factory()->enCurso()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/campanias/{$campania->id}/iniciar");

    $response->assertStatus(400);
});

test('cancelar campaña planificada', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
        'estado'         => EstadoCampania::PLANIFICADA,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/campanias/{$campania->id}/cancelar");

    $response->assertOk();

    $this->assertDatabaseHas('campanias', [
        'id'     => $campania->id,
        'estado' => EstadoCampania::CANCELADA->value,
    ]);
});

test('cancelar campaña en curso', function () {
    $campania = Campania::factory()->enCurso()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/campanias/{$campania->id}/cancelar");

    $response->assertOk();

    $this->assertDatabaseHas('campanias', [
        'id'     => $campania->id,
        'estado' => EstadoCampania::CANCELADA->value,
    ]);
});

test('no puede cancelar campaña ya finalizada', function () {
    $campania = Campania::factory()->finalizada()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/campanias/{$campania->id}/cancelar");

    $response->assertStatus(400);
});

test('no puede editar campaña finalizada', function () {
    $campania = Campania::factory()->finalizada()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->putJson("/api/campanias/{$campania->id}", [
            'nombre'            => 'Intentar Editar',
            'descripcion'       => 'No debería funcionar',
            'lugar'             => 'Lugar',
            'fecha_hora_inicio' => now()->toDateTimeString(),
            'fecha_hora_fin'    => now()->addDays(7)->toDateTimeString(),
            'responsable_id'    => $this->user->personal->id,
        ]);

    $response->assertStatus(400);
});

test('no puede eliminar campaña en curso', function () {
    $campania = Campania::factory()->enCurso()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->deleteJson("/api/campanias/{$campania->id}");

    $response->assertStatus(400);
    $this->assertDatabaseHas('campanias', ['id' => $campania->id]);
});

// ─── ESTADÍSTICAS ────────────────────────────────────────────────

test('obtener estadísticas de campaña', function () {
    $campania = Campania::factory()->create([
        'responsable_id' => $this->user->personal->id,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson("/api/campanias/{$campania->id}/estadisticas");

    $response->assertOk()
        ->assertJsonStructure([
            'campania_id',
            'resumen_general' => [
                'total_vacunas_aplicadas',
                'total_desparasitaciones_aplicadas',
                'total_intervenciones',
            ],
            'desglose_vacunas',
            'desglose_desparasitaciones',
        ]);
});
