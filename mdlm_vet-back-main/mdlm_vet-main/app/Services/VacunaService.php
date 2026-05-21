<?php

namespace App\Services;

use App\Enums\TipoMovimientoInventario;
use App\DTOs\VacunaAnimal\CreateVacunaDTO;
use App\DTOs\VacunaAnimal\UpdateVacunaDTO;
use App\DTOs\AjusteStockDTO;
use App\Services\Contracts\MedicamentoServiceInterface;
use App\Models\Consulta;
use App\Models\Medicamento;
use App\Models\EsquemaVacuna;
use App\Models\VacunaAnimal;
use App\Services\Contracts\VacunaServiceInterface;
use App\Services\Contracts\InventarioServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VacunaService implements VacunaServiceInterface
{
    public function __construct(private readonly InventarioServiceInterface $inventarioService){}

    public function getAll(): Collection
    {
        return VacunaAnimal::all();
    }

    public function getById(string $id): VacunaAnimal
    {
        return VacunaAnimal::findOrFail($id);
    }

    public function create(CreateVacunaDTO $dto): VacunaAnimal
    {
        return DB::transaction(function () use ($dto) {
            if ($dto->campania_id) {
                $campania = Campania::findOrFail($dto->campania_id);
                if ($campania->estado !== EstadoCampania::EN_CURSO) throw new Exception("No se puede registrar una desparasitación a una campaña que no está EN CURSO.");
            }
            
            $esquema_vacuna = EsquemaVacuna::where('codigo', $dto->esquema_vacuna_id)->firstOrFail();
            $medicamento = Medicamento::where('codigo', $dto->medicamento_id)->firstOrFail();

            $vacuna = VacunaAnimal::create([
                'animal_id' => $dto->animal_id,
                'esquema_vacuna_id' => $esquema_vacuna->id,
                'medicamento_id'    => $medicamento->id,
                'personal_id'       => $dto->personal_id,
                'consulta_id'       => $dto->consulta_id,
                'fecha_aplicacion'  => $dto->fecha_aplicacion,
                'fecha_proxima'     => $dto->fecha_proxima,
                'dosis'             => $dto->dosis,
                'cantidad'          => $dto->cantidad,
                'lote'              => $dto->lote,
                'fabricante'        => $dto->fabricante,
                'observaciones'     => $dto->observaciones,
                'campania_id'       => $dto->campania_id,
            ]);

            // DESCUENTO DE INVENTARIO SOLO SI NO ES PARTE DE UNA CAMPAÑA, YA QUE EN ESE CASO EL DESCUENTO SE HACE AL FINALIZAR LA CAMPAÑA
            if(empty($dto->campania_id)) {
                $this->inventarioService->registrarMovimiento(
                    medicamento_id: $medicamento->id,
                    cantidad: $dto->cantidad,
                    tipo: TipoMovimientoInventario::SALIDA,
                    motivo: "Aplicación de vacuna",
                    personal_id: $dto->personal_id,
                    referencia: $vacuna
                );
            }

            return $vacuna;
        });
    }

    public function update(UpdateVacunaDTO $dto, string $id): VacunaAnimal
    {
        $vacuna = VacunaAnimal::findOrFail($id);
        $vacuna->update($dto->toArray());

        return $vacuna;
    }

    public function delete(string $id): void
    {
        $vacuna = VacunaAnimal::findOrFail($id);
        $vacuna->delete();
    }
}
