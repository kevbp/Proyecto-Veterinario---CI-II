<?php

namespace App\Services;

use App\DTOs\Animal\CreateAnimalDTO;
use App\DTOs\Animal\UpdateAnimalDTO;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Propietario;
use App\Services\Contracts\AnimalServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AnimalService implements AnimalServiceInterface
{
    public function getAll(): Collection
    {
        return Animal::with(['propietario'])->get();
    }

    public function getById(string $id): Animal
    {
        $animal = Animal::with(['propietario'])->find($id);

        if (!$animal) {
            throw new ModelNotFoundException();
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
        $data = $dto->toArray();
        
        // Convertir codigo a especie_id
        if (isset($data['especie'])) {
            $especie = Especie::where('codigo', $data['especie'])->firstOrFail();
            $data['especie_id'] = $especie->id;
            unset($data['especie']);
        }

        return Animal::create($data);
    }

    public function update(string $id, UpdateAnimalDTO $dto): Animal
    {
        $animal = $this->getById($id);
        $data = $dto->toArray();
        
        // Convertir codigo a especie_id
        if (isset($data['especie'])) {
            $especie = Especie::where('codigo', $data['especie'])->firstOrFail();
            $data['especie_id'] = $especie->id;
            unset($data['especie']);
        }

        $animal->update($data);
        return $animal->fresh();
    }

    public function delete(string $id): void
    {
        $animal = $this->getById($id);
        $animal->delete();
    }
}