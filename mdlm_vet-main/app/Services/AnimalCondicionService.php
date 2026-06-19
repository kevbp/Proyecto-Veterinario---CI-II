<?php

namespace App\Services;

use App\DTOs\Animal_Condicion\CreateAnimalCondicionDTO;
use App\DTOs\Animal_Condicion\UpdateAnimalCondicionDTO;
use App\Models\Animal_Condicion;
use App\Models\CatalogoCondiciones;
use App\Services\Contracts\AnimalCondicionServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class AnimalCondicionService implements AnimalCondicionServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Animal_Condicion::with(['condicion'])->paginate(25);
    }

    public function getById(string $id): ?Animal_Condicion
    {
        return Animal_Condicion::with(['condicion'])->findOrFail($id);
    }

    public function create(CreateAnimalCondicionDTO $dto): Animal_Condicion
    {
        $data = $dto->toArray();
        $data['fecha_diagnostico'] = now()->format('Y-m-d');

        return Animal_Condicion::create($data);
    }

    public function update(string $id, UpdateAnimalCondicionDTO $dto): Animal_Condicion
    {
        $animal_Condicion = Animal_Condicion::findOrFail($id);
        $animal_Condicion->update($dto->toArray());

        return $animal_Condicion->fresh(['condicion']);
    }

    public function delete(string $id): void
    {
        $animal_Condicion = Animal_Condicion::findOrFail($id);
        $animal_Condicion->delete();
    }
}
