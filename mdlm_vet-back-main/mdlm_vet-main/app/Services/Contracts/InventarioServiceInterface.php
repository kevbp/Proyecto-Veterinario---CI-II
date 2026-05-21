<?php

namespace App\Services\Contracts;

use App\Enums\TipoMovimientoInventario;
use App\Models\MovimientoInventario;
use Illuminate\Database\Eloquent\Model;

interface InventarioServiceInterface
{
    /**
     * Registra un movimiento en el Kardex y actualiza el stock físico de forma segura.
     *
     * @param string $medicamento_id El UUID del frasco/medicamento
     * @param float $cantidad Cuánto entra o sale (siempre en positivo)
     * @param TipoMovimientoInventario $tipo ENTRADA, SALIDA, MERMA, etc.
     * @param string $motivo Descripción corta (ej. "Atención en clínica")
     * @param string $personal_id Quién realiza la acción
     * @param Model|null $referencia El modelo polimórfico (Vacuna, Desparasitación, Campaña)
     */
    public function registrarMovimiento(
        string $medicamento_id,
        float $cantidad,
        TipoMovimientoInventario $tipo,
        string $motivo,
        string $personal_id,
        ?Model $referencia = null,
    ): MovimientoInventario;

    public function registroMasivo(
        array $items, // Array de movimientos con los mismos campos que registrarMovimiento()
        string $motivo,
        string $personal_id,
    ): array; // Retorna un array de MovimientoInventario creados

    public function registrarMermas(
        array $mermas, // Array de mermas con los mismos campos que registrarMovimiento()
        string $personal_id,
    ): array; // Retorna un array de MovimientoInventario creados
}
