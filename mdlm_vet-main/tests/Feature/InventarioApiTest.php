<?php

use App\Models\Medicamento;
use App\Enums\TipoMovimientoInventario;
use App\Services\InventarioService;

/*
|--------------------------------------------------------------------------
| Feature Tests — Inventario / Kardex
|--------------------------------------------------------------------------
*/

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
    $this->inventarioService = app(InventarioService::class);
});

// ─── MOVIMIENTOS DE INVENTARIO ───────────────────────────────────

test('movimiento de entrada aumenta stock', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 100]);

    $movimiento = $this->inventarioService->registrarMovimiento(
        medicamento_id: $medicamento->id,
        cantidad: 50,
        tipo: TipoMovimientoInventario::ENTRADA,
        motivo: 'Compra de insumos',
        personal_id: $this->user->personal->id,
    );

    expect($movimiento->stock_actual)->toBe(150.0)
        ->and($movimiento->stock_anterior)->toBe(100.0);

    $this->assertDatabaseHas('medicamentos', [
        'id'    => $medicamento->id,
        'stock' => 150,
    ]);
});

test('movimiento de salida reduce stock', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 100]);

    $movimiento = $this->inventarioService->registrarMovimiento(
        medicamento_id: $medicamento->id,
        cantidad: 30,
        tipo: TipoMovimientoInventario::SALIDA,
        motivo: 'Uso en consulta',
        personal_id: $this->user->personal->id,
    );

    expect($movimiento->stock_actual)->toBe(70.0);

    $this->assertDatabaseHas('medicamentos', [
        'id'    => $medicamento->id,
        'stock' => 70,
    ]);
});

test('movimiento de merma reduce stock', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 50]);

    $movimiento = $this->inventarioService->registrarMovimiento(
        medicamento_id: $medicamento->id,
        cantidad: 5,
        tipo: TipoMovimientoInventario::MERMA,
        motivo: 'Frasco dañado',
        personal_id: $this->user->personal->id,
    );

    expect($movimiento->stock_actual)->toBe(45.0);
});

test('stock insuficiente lanza excepción', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 10]);

    expect(function () use ($medicamento) {
        $this->inventarioService->registrarMovimiento(
            medicamento_id: $medicamento->id,
            cantidad: 50,
            tipo: TipoMovimientoInventario::SALIDA,
            motivo: 'Intento con stock insuficiente',
            personal_id: $this->user->personal->id,
        );
    })->toThrow(\Exception::class, 'Stock insuficiente');
});

test('cantidad negativa o cero lanza excepción', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 100]);

    expect(function () use ($medicamento) {
        $this->inventarioService->registrarMovimiento(
            medicamento_id: $medicamento->id,
            cantidad: 0,
            tipo: TipoMovimientoInventario::ENTRADA,
            motivo: 'Cantidad inválida',
            personal_id: $this->user->personal->id,
        );
    })->toThrow(\Exception::class, 'mayor a cero');
});

test('cantidad negativa lanza excepción', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 100]);

    expect(function () use ($medicamento) {
        $this->inventarioService->registrarMovimiento(
            medicamento_id: $medicamento->id,
            cantidad: -5,
            tipo: TipoMovimientoInventario::ENTRADA,
            motivo: 'Cantidad negativa',
            personal_id: $this->user->personal->id,
        );
    })->toThrow(\Exception::class, 'mayor a cero');
});

// ─── INGRESO MASIVO ──────────────────────────────────────────────

test('ingreso masivo aumenta stock de múltiples medicamentos', function () {
    $med1 = Medicamento::factory()->create(['stock' => 100]);
    $med2 = Medicamento::factory()->create(['stock' => 200]);

    $items = [
        ['medicamento_id' => $med1->id, 'cantidad' => 25],
        ['medicamento_id' => $med2->id, 'cantidad' => 50],
    ];

    $movimientos = $this->inventarioService->registroMasivo(
        items: $items,
        motivo: 'Ingreso por factura',
        personal_id: $this->user->personal->id,
    );

    expect($movimientos)->toHaveCount(2);

    $this->assertDatabaseHas('medicamentos', ['id' => $med1->id, 'stock' => 125]);
    $this->assertDatabaseHas('medicamentos', ['id' => $med2->id, 'stock' => 250]);
});

// ─── REGISTRO DE MERMAS ──────────────────────────────────────────

test('registro de mermas reduce stock múltiples medicamentos', function () {
    $med1 = Medicamento::factory()->create(['stock' => 100]);
    $med2 = Medicamento::factory()->create(['stock' => 80]);

    $mermas = [
        ['medicamento_id' => $med1->id, 'cantidad' => 3, 'motivo' => 'Frasco roto'],
        ['medicamento_id' => $med2->id, 'cantidad' => 2, 'motivo' => 'Producto vencido'],
    ];

    $movimientos = $this->inventarioService->registrarMermas(
        mermas: $mermas,
        personal_id: $this->user->personal->id,
    );

    expect($movimientos)->toHaveCount(2);

    $this->assertDatabaseHas('medicamentos', ['id' => $med1->id, 'stock' => 97]);
    $this->assertDatabaseHas('medicamentos', ['id' => $med2->id, 'stock' => 78]);
});

// ─── HISTORIAL KARDEX ────────────────────────────────────────────

test('movimiento crea registro en movimiento_inventarios', function () {
    $medicamento = Medicamento::factory()->create(['stock' => 200]);

    $this->inventarioService->registrarMovimiento(
        medicamento_id: $medicamento->id,
        cantidad: 10,
        tipo: TipoMovimientoInventario::ENTRADA,
        motivo: 'Test de kardex',
        personal_id: $this->user->personal->id,
    );

    $this->assertDatabaseHas('movimiento_inventarios', [
        'medicamento_id'     => $medicamento->id,
        'tipo_movimiento'    => 'entrada',
        'cantidad_movimiento'=> 10,
        'motivo'             => 'Test de kardex',
    ]);
});
