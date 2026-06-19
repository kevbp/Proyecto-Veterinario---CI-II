<?php

use App\Models\Campania;
use App\Enums\EstadoCampania;

/*
|--------------------------------------------------------------------------
| Feature Tests — Vistas Públicas
|--------------------------------------------------------------------------
*/

test('usuario no autenticado puede listar campañas activas', function () {
    // Las campañas activas son aquellas en estado PLANIFICADA o EN_CURSO
    Campania::factory()->count(2)->create([
        'estado' => EstadoCampania::PLANIFICADA,
        'responsable_id' => \App\Models\Personal::factory()->create()->id,
    ]);

    Campania::factory()->count(1)->create([
        'estado' => EstadoCampania::EN_CURSO,
        'responsable_id' => \App\Models\Personal::factory()->create()->id,
    ]);

    // Campañas cerradas/canceladas no deben listarse
    Campania::factory()->count(2)->create([
        'estado' => EstadoCampania::CANCELADA,
        'responsable_id' => \App\Models\Personal::factory()->create()->id,
    ]);

    // La ruta pública según api.php
    $response = $this->getJson('/api/public/campanias-activas'); 
    // Reemplazaremos public/campanias-activas dependiendo del Select-String

    if ($response->status() === 404) {
        $response = $this->getJson('/api/campanias-activas');
    }

    $response->assertOk()
        ->assertJsonStructure(['data']);
        
    // Deberían haber devuelto 3 campañas (2 planificadas, 1 en curso)
    expect(count($response->json('data')))->toBe(3);
});
