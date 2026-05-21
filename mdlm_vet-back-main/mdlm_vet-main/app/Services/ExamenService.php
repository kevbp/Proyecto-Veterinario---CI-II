<?php

namespace App\Services;

use App\Models\Examen;
use App\Models\Consulta;
use App\Models\TipoExamen;
use App\DTOs\Examen\CreateExamenDTO;
use App\DTOs\Examen\UpdateExamenDTO;
use App\Services\Contracts\ExamenServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ExamenService implements ExamenServiceInterface
{
    public function getAll(): Collection
    {
        return Examen::all();
    }

    public function getById(string $id): Examen
    {
        return Examen::findOrFail($id);
    }

    public function create(CreateExamenDTO $dto): Examen
    {
        $data = $dto->toArray();

        $data['tipo_examen_id'] = TipoExamen::where('codigo', $data['tipo_examen_id'])->firstOrFail()->id;
        
        // Obtener animal_id de la consulta asociada si existe
        if (isset($data['consulta_id']) && !is_null($data['consulta_id'])) {
            $consulta = Consulta::findOrFail($data['consulta_id']);
            $data['animal_id'] = $consulta->animal_id;
        } else {
            unset($data['consulta_id']);
        }
        
        return Examen::create($data);
    }

    public function update(string $id, UpdateExamenDTO $dto): Examen
    {
        $examen = Examen::findOrFail($id);
        $data = $dto->toArray();
        
        // Convertir tipo_examen_id de código a UUID si está presente
        if (isset($data['tipo_examen_id'])) {
            $data['tipo_examen_id'] = TipoExamen::where('codigo', $data['tipo_examen_id'])->firstOrFail()->id;
        }
        
        // Si consulta_id está presente y es null, eliminarlo del array
        if (isset($data['consulta_id']) && is_null($data['consulta_id'])) {
            unset($data['consulta_id']);
        }
        
        $examen->update($data);

        return $examen->fresh();
    }

    public function delete(string $id): void
    {
        $examen = Examen::findOrFail($id);
        $examen->delete();
    }    
}
