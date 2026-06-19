<?php

namespace App\Services;

use App\DTOs\Animal\CreateAnimalDTO;
use App\DTOs\Animal\UpdateAnimalDTO;
use App\Models\Animal;
use App\Models\Propietario;
use App\Services\Contracts\AnimalServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AnimalService implements AnimalServiceInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator|Collection|array
    {
        $query = Animal::with(['propietario', 'especie', 'raza', 'alergias.alergia', 'condiciones.condicion']);

        if (isset($filters['albergue'])) {
            $query->whereHas('propietario', function ($q) use ($filters) {
                $operator = $filters['albergue'] ? '=' : '!=';
                $q->where('email', $operator, 'veterinaria@munimolina.gob.pe');
            });
        }

        if (isset($filters['propietario_id'])) {
            $query->where('propietario_id', $filters['propietario_id']);
        }

        return $query->paginate(25);
    }

    public function getById(string $id): Animal
    {
        $animal = Animal::with(['propietario', 'especie', 'raza', 'alergias.alergia', 'condiciones.condicion'])->find($id);

        if (! $animal) {
            throw new ModelNotFoundException;
        }

        return $animal;
    }

    public function getPropietarioByAnimalId(string $id): Propietario
    {
        $animal = $this->getById($id);

        return $animal->propietario;
    }

    public function create(CreateAnimalDTO $dto): Animal
    {
        return Animal::create($dto->toArray());
    }

    public function update(string $id, UpdateAnimalDTO $dto): Animal
    {
        $animal = $this->getById($id);
        $animal->update($dto->toArray());

        return $animal->fresh(['propietario', 'especie', 'raza', 'alergias.alergia', 'condiciones.condicion']);
    }

    public function delete(string $id): void
    {
        $animal = $this->getById($id);
        $animal->delete();
    }

    public function registrarFallecimiento(string $id): Animal
    {
        $animal = $this->getById($id);

        if ($animal->fallecido) {
            throw new \InvalidArgumentException('El animal ya se encuentra registrado como fallecido.');
        }

        $animal->update([
            'fallecido' => true,
            'fecha_fallecimiento' => now()->format('Y-m-d'),
        ]);

        return $animal->fresh(['propietario', 'especie', 'raza', 'alergias.alergia', 'condiciones.condicion']);
    }
}
