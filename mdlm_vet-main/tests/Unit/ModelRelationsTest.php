<?php

use App\Models\Animal;
use App\Models\Campania;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Especie;
use App\Models\EstadoCita;
use App\Models\Medicamento;
use App\Models\Personal;
use App\Models\Propietario;
use App\Models\Raza;
use App\Models\TipoDocumento;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Tests Unitarios — Relaciones de Modelos
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    seedCatalogosBase();
});

// ─── Animal ──────────────────────────────────────────────────────

test('animal pertenece a un propietario', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first() ?? Raza::factory()->create(['especie_id' => $especie->id]);

    $animal = Animal::factory()->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    expect($animal->propietario)->toBeInstanceOf(Propietario::class)
        ->and($animal->propietario->id)->toBe($propietario->id);
});

test('animal pertenece a una especie y raza', function () {
    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first() ?? Raza::factory()->create(['especie_id' => $especie->id]);

    $animal = Animal::factory()->create([
        'propietario_id' => Propietario::factory()->create(['tipo_doc_id' => TipoDocumento::first()->id])->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    expect($animal->especie)->toBeInstanceOf(Especie::class)
        ->and($animal->raza)->toBeInstanceOf(Raza::class);
});

// ─── Propietario ─────────────────────────────────────────────────

test('propietario tiene muchos animales', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
    ]);

    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first() ?? Raza::factory()->create(['especie_id' => $especie->id]);

    Animal::factory()->count(3)->create([
        'propietario_id' => $propietario->id,
        'especie_id'     => $especie->id,
        'raza_id'        => $raza->id,
    ]);

    expect($propietario->animales)->toHaveCount(3);
});

test('propietario pertenece a un tipo de documento', function () {
    $tipoDoc = TipoDocumento::first();
    $propietario = Propietario::factory()->create(['tipo_doc_id' => $tipoDoc->id]);

    expect($propietario->tipoDocumento)->toBeInstanceOf(TipoDocumento::class)
        ->and($propietario->tipoDocumento->id)->toBe($tipoDoc->id);
});

test('propietario estaVinculado retorna false si no tiene user', function () {
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
        'user_id'     => null,
    ]);

    expect($propietario->estaVinculado())->toBeFalse();
});

test('propietario estaVinculado retorna true si tiene user', function () {
    $user = User::factory()->create();
    $propietario = Propietario::factory()->create([
        'tipo_doc_id' => TipoDocumento::first()->id,
        'user_id'     => $user->id,
    ]);

    expect($propietario->estaVinculado())->toBeTrue();
});

// ─── Campaña ─────────────────────────────────────────────────────

test('campaña tiene responsable (personal)', function () {
    $user = crearUsuarioConRol('veterinario');
    $campania = Campania::factory()->create([
        'responsable_id' => $user->personal->id,
    ]);

    expect($campania->responsable)->toBeInstanceOf(Personal::class);
});

// ─── Raza ────────────────────────────────────────────────────────

test('raza pertenece a una especie', function () {
    $especie = Especie::first();
    $raza = Raza::where('especie_id', $especie->id)->first();

    expect($raza->especie)->toBeInstanceOf(Especie::class)
        ->and($raza->especie->id)->toBe($especie->id);
});

// ─── User ────────────────────────────────────────────────────────

test('user tiene helpers de rol funcionales', function () {
    $admin = crearUsuarioConRol('admin');

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->isVeterinario())->toBeFalse()
        ->and($admin->isCliente())->toBeFalse();
});

test('user tiene relación hasOne personal', function () {
    $user = crearUsuarioConRol('veterinario');

    expect($user->personal)->toBeInstanceOf(Personal::class);
});
