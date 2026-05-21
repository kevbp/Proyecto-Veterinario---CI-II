<?php

namespace App\Services;

use App\Models\Consulta;
use App\Models\Desparasitacion;
use App\Models\Medicamento;
use App\DTOs\Desparasitacion\CreateDesparasitacionDTO;
use App\DTOs\Desparasitacion\UpdateDesparasitacionDTO;
use App\Services\Contracts\DesparasitacionServiceInterface;
use App\Services\Contracts\InventarioServiceInterface;
use App\Enums\TipoMovimientoInventario;
use App\Enums\EstadoCampania;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class DesparasitacionService implements DesparasitacionServiceInterface
{
    public function __construct(private InventarioServiceInterface $inventarioService) {}

    public function getAll(): Collection
    {
        return Desparasitacion::all();
    }

    public function getById(string $id): ?Desparasitacion
    {
        return Desparasitacion::find($id);
    }

    public function create(CreateDesparasitacionDTO $dto): Desparasitacion
    {
        return DB::transaction(function () use ($dto) {

            if ($dto->campania_id) {
                $campania = Campania::findOrFail($dto->campania_id);
                if ($campania->estado !== EstadoCampania::EN_CURSO) throw new Exception("No se puede registrar una desparasitación a una campaña que no está EN CURSO.");
            }

            $medicamento = Medicamento::where('codigo', $dto->medicamento_id)->firstOrFail();

            $desparasitacion = Desparasitacion::create([
                'animal_id'             => $dto->animal_id,
                'medicamento_id'        => $medicamento->id,
                'fecha_aplicacion'      => $dto->fecha_aplicacion,
                'fecha_aplicacion_sgte' => $dto->fecha_aplicacion_sgte,
                'dosis'                 => $dto->dosis,
                'via'                   => $dto->via,
                'observaciones'         => $dto->observaciones,
                'cantidad'              => $dto->cantidad,
                'personal_id'           => $dto->personal_id,
                'consulta_id'           => $dto->consulta_id,
                'campania_id'           => $dto->campania_id ?? null,
            ]);

            if (empty($dto->campania_id)) {
                $this->inventarioService->registrarMovimiento(
                    medicamento_id: $medicamento->id, // o el del DTO
                    cantidad: $dto->cantidad,
                    tipo: TipoMovimientoInventario::SALIDA,
                    motivo: "Desparasitación en clínica",
                    personal_id: $dto->personal_id,
                    referencia: $desparasitacion
                );
            }

            return $desparasitacion;
        });
    }

    public function update(UpdateDesparasitacionDTO $dto, string $id): Desparasitacion
    {
        $desparasitacion = Desparasitacion::findOrFail($id);
        $desparasitacion->update($dto->toArray());

        return $desparasitacion;
    }

    public function delete(string $id): void
    {
        $desparasitacion = Desparasitacion::findOrFail($id);
        $desparasitacion->delete();
    }
}
