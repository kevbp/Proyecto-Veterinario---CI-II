<?php

namespace App\Services;

use App\DTOs\Consulta\CreateConsultaDTO;
use App\DTOs\Consulta\UpdateConsultaDTO;
use App\Models\Consulta;
use App\Models\Cita;
use App\Models\EstadoCita;
use App\Services\Contracts\ConsultaServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class ConsultaService implements ConsultaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Consulta::with(['animal', 'personal', 'cita'])->paginate(25);
    }

    public function getById(string $id): Consulta
    {
        return Consulta::findOrFail($id);
    }

    public function create(CreateConsultaDTO $dto): Consulta
    {
        $citaId = $dto->cita_id;
        $animalId = $dto->animal_id;

        // Si viene cita_id, validamos que exista y extraemos el animal_id de la cita.
        if ($citaId) {
            $cita = Cita::findOrFail($citaId);
            $animalId = $cita->animal_id; 
        } else {
            // Si no viene cita_id, generamos una cita automática de "ATENCIÓN INMEDIATA".
            $estadoCompletada = EstadoCita::where('codigo', 'COMPLETADA')->first();

            $nuevaCita = Cita::create([
                'fecha_hora'     => now(),
                'motivo'         => 'Atención Inmediata / ' . $dto->motivo,
                'estado_cita_id' => $estadoCompletada ? $estadoCompletada->id : null,
                'animal_id'      => $animalId, // El animal_id de la cita será el mismo que el de la consulta.
                'personal_id'    => $dto->personal_id, // El personal_id de la cita será el mismo que el de la consulta.
            ]);

            $citaId = $nuevaCita->id;
        }

        $consultaData = array_merge($dto->toArray(), [
            'cita_id'    => $citaId,
            'animal_id'  => $animalId,
            'fecha_hora' => now(),
        ]);

        $consulta = Consulta::create($consultaData);

        return $consulta;
    }

    public function update(string $id, UpdateConsultaDTO $dto): Consulta
    {
        $consulta = Consulta::findOrFail($id);
        $dataToUpdate = array_filter($dto->toArray(), function ($value, $key) {
            $nullableFields = ['diagnostico', 'tratamiento', 'peso_registrado', 'observaciones'];
            return $value !== null || in_array($key, $nullableFields);
        }, ARRAY_FILTER_USE_BOTH);
        $consulta->update($dataToUpdate);

        return $consulta;
    }

    public function delete(string $id): void
    {
        $consulta = Consulta::findOrFail($id);
        $consulta->delete();
    }
}
