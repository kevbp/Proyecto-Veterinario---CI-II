<?php

namespace App\Services;

use App\Models\Medicamento;
use App\Models\MovimientoInventario;
use App\Enums\TipoMovimientoInventario;
use App\Services\Contracts\InventarioServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class InventarioService implements InventarioServiceInterface
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
    ): MovimientoInventario {
        
        // Validación de seguridad: La cantidad a mover jamás debe ser negativa.
        // Si quieren restar, para eso está el $tipo = SALIDA.
        if ($cantidad <= 0) {
            throw new Exception("La cantidad del movimiento debe ser mayor a cero.");
        }

        return DB::transaction(function () use (
            $medicamento_id, $cantidad, $tipo, $motivo, $personal_id, $referencia
        ) {
            // 1. BLOQUEO DE CONCURRENCIA: lockForUpdate()
            // Evita que otro proceso modifique este medicamento mientras hacemos la matemática.
            $medicamento = Medicamento::where('id', $medicamento_id)->lockForUpdate()->firstOrFail();

            $stock_anterior = (float) $medicamento->stock;

            $stock_actual = in_array($tipo, [TipoMovimientoInventario::ENTRADA, TipoMovimientoInventario::AJUSTE]) 
                ? $stock_anterior + $cantidad 
                : $stock_anterior - $cantidad;

            if ($stock_actual < 0) throw new Exception("Stock insuficiente para: {$medicamento->nombre}.");

            // 4. Crear el historial inmutable (El Kardex)
            $movimiento = MovimientoInventario::create([
                'medicamento_id' => $medicamento->id,
                'tipo_movimiento' => $tipo->value, // Guardamos el valor del Enum
                'cantidad_movimiento' => $cantidad,
                'stock_anterior' => $stock_anterior,
                'stock_actual' => $stock_actual,
                'motivo' => $motivo,
                'personal_id' => $personal_id,
                // Magia polimórfica: Si enviamos un modelo, extrae su ID y su Clase
                'referencia_id' => $referencia?->id,
                'referencia_tipo' => $referencia ? get_class($referencia) : null,
            ]);

            // 5. Actualizar la "foto" del stock actual en la tabla medicamentos
            $medicamento->update(['stock' => $stock_actual]);

            return $movimiento;
        });
    }

    // 2. REGISTRO MASIVO (Para ingreso de facturas/compras al almacén)
    public function registroMasivo(array $items, string $motivo, string $personal_id): array
    {
        return DB::transaction(function () use ($items, $motivo, $personal_id) {
            $movimientos = [];
            // $items espera: [['medicamento_id' => '...', 'cantidad' => 50], ...]
            foreach ($items as $item) {
                $movimientos[] = $this->registrarMovimiento(
                    medicamento_id: $item['medicamento_id'],
                    cantidad: $item['cantidad'],
                    tipo: TipoMovimientoInventario::ENTRADA,
                    motivo: $motivo,
                    personal_id: $personal_id
                );
            }
            return $movimientos;
        });
    }

    // 3. REGISTRO DE MERMAS (Fin de día: frascos rotos, vencidos, abiertos)
    public function registrarMermas(array $mermas, string $personal_id): array
    {
        return DB::transaction(function () use ($mermas, $personal_id) {
            $movimientos = [];
            // $mermas espera: [['medicamento_id' => '...', 'cantidad' => 2.5, 'motivo' => 'Frasco abierto caducado'], ...]
            foreach ($mermas as $merma) {
                $movimientos[] = $this->registrarMovimiento(
                    medicamento_id: $merma['medicamento_id'],
                    cantidad: $merma['cantidad'],
                    tipo: TipoMovimientoInventario::MERMA, // O SALIDA, según tu Enum
                    motivo: $merma['motivo'] ?? 'Merma de fin de día',
                    personal_id: $personal_id
                );
            }
            return $movimientos;
        });
    }
}