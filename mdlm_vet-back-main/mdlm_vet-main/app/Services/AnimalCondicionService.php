<?php

namespace App\Services;

use App\DTOs\Animal_Condicion\CreateAnimalCondicionDTO;
use App\DTOs\Animal_Condicion\UpdateAnimalCondicionDTO;
use App\Models\Animal_Condicion;
use App\Models\CatalogoCondiciones;
use App\Services\Contracts\AnimalCondicionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class AnimalCondicionService implements AnimalCondicionServiceInterface
{
    public function getAll(): Collection
    {
        return Animal_Condicion::all();
    }

    public function getById(string $id): ?Animal_Condicion
    {
        return Animal_Condicion::findOrFail($id);
    }

    public function create(CreateAnimalCondicionDTO $dto): Animal_Condicion
    {
        $data = $dto->toArray();
        $data['condicion_id'] = CatalogoCondiciones::where('codigo', $data['condicion_id'])->firstOrFail()->id;

        return Animal_Condicion::create($data);
    }

    public function update(string $id, UpdateAnimalCondicionDTO $dto): Animal_Condicion
    {
        $animal_Condicion = Animal_Condicion::findOrFail($id);
        $data = $dto->toArray();
        $data['condicion_id'] = CatalogoCondiciones::where('codigo', $data['condicion_id'])->firstOrFail()->id;
        $animal_Condicion->update($data);

        return $animal_Condicion->fresh();
    }

    public function delete(string $id): void
    {
        $animal_Condicion = Animal_Condicion::findOrFail($id);
        $animal_Condicion->delete();
    }
}
