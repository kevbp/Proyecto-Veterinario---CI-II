<?php

namespace App\Services;

use App\Models\Receta;
use App\Models\LineaMedicamento;
use App\Models\Medicamento;
use App\DTOs\Receta\CreateRecetaDTO;
use App\DTOs\Receta\UpdateRecetaDTO;
use App\DTOs\AjusteStockDTO;
use App\Services\Contracts\RecetaServiceInterface;
use App\Services\Contracts\MedicamentoServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Enums\TipoMovimientoInventario;
use App\Services\Contracts\InventarioServiceInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class RecetaService implements RecetaServiceInterface
{
    public function __construct(
        private readonly InventarioServiceInterface $inventarioService
    ) {}

    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Receta::with(['lineasMedicamentos.medicamento'])->paginate(25);
    }

    public function getById(string $id): ?Receta
    {
        return Receta::with(['lineasMedicamentos.medicamento'])->findOrFail($id);
    }

    public function create(CreateRecetaDTO $dto): Receta
    {
        return DB::transaction(function () use ($dto) {
            $receta = Receta::create([
                'consulta_id' => $dto->consulta_id,
                'estado_receta' => $dto->estado_receta,
                'indicaciones_generales' => $dto->indicaciones_generales,
                'fecha_emision' => $dto->fecha_emision,
                'fecha_vencimiento' => $dto->fecha_vencimiento
            ]);

            foreach ($dto->lineas_medicamentos as $linea) {
                // Buscamos por ID (UUID) para mantener el estándar
                $medicamento = Medicamento::findOrFail($linea['medicamento_id']);
                
                LineaMedicamento::create([
                    'receta_id' => $receta->id,
                    'medicamento_id' => $medicamento->id,
                    'cantidad' => $linea['cantidad'],
                    'dosis' => $linea['dosis'],
                    'frecuencia' => $linea['frecuencia'],
                    'duracion' => $linea['duracion'],
                    'instruccion_especifica' => $linea['instruccion_especifica'] ?? null
                ]);

                // Registrar movimiento de salida en el inventario
                $this->inventarioService->registrarMovimiento(
                    medicamento_id: $medicamento->id,
                    cantidad: $linea['cantidad'],
                    tipo: TipoMovimientoInventario::SALIDA,
                    motivo: "Venta/Despacho de receta médica",
                    personal_id: auth('api')->user()->personal->id ?? null,
                    referencia: $receta
                );
            }
            
            return $receta->load('lineasMedicamentos.medicamento');
        });
    }

    public function update(string $id, UpdateRecetaDTO $dto): Receta
    {
        $receta = Receta::findOrFail($id);
        $receta->update($dto->toArray());

        return $receta->fresh();
    }

    public function delete(string $id): void
    {
        $receta = Receta::findOrFail($id);

        $receta->delete();
    }
}
