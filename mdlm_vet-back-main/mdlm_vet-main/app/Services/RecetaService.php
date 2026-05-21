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
use Illuminate\Support\Facades\DB;
use Exception;

class RecetaService implements RecetaServiceInterface
{
    public function __construct(private readonly MedicamentoServiceInterface $medicamentoService){}

    public function getAll(): Collection
    {
        return Receta::all();
    }

    public function getById(string $id): ?Receta
    {
        return Receta::findOrFail($id);
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
                $medicamento = Medicamento::where('codigo', $linea['medicamento_id'])->first();
                
                if (!$medicamento) {
                    throw new Exception("Medicamento con código {$linea['medicamento_id']} no encontrado");
                }

                LineaMedicamento::create([
                    'receta_id' => $receta->id,
                    'medicamento_id' => $medicamento->id,
                    'cantidad' => $linea['cantidad'],
                    'dosis' => $linea['dosis'],
                    'frecuencia' => $linea['frecuencia'],
                    'duracion' => $linea['duracion'],
                    'instruccion_especifica' => $linea['instruccion_especifica'] ?? null
                ]);

                $ajusteStockDTO = new AjusteStockDTO(
                    medicamento_id: $medicamento->id,
                    cantidad: $linea['cantidad']
                );
                $this->medicamentoService->restarStock($ajusteStockDTO);
            }
            return $receta->load('lineasMedicamentos');
            
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