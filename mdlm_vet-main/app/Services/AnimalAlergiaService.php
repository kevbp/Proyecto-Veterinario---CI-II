<?php

namespace App\Services;

use App\DTOs\Animal_Alergia\CreateAnimalAlergiaDTO;
use App\DTOs\Animal_Alergia\UpdateAnimalAlergiaDTO;
use App\Models\Animal_Alergia;
use App\Models\CatalogoAlergias;
use App\Services\Contracts\AnimalAlergiaServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class AnimalAlergiaService implements AnimalAlergiaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Animal_Alergia::with(['alergia'])->paginate(25);
    }

    public function getById(string $id): ?Animal_Alergia
    {
        return Animal_Alergia::with(['alergia'])->findOrFail($id);
    }

    public function create(CreateAnimalAlergiaDTO $dto): Animal_Alergia
    {
        return Animal_Alergia::create($dto->toArray());
    }

    public function update(string $id, UpdateAnimalAlergiaDTO $dto): Animal_Alergia
    {
        $animalAlergia = Animal_Alergia::findOrFail($id);
        $animalAlergia->update($dto->toArray());

        return $animalAlergia->fresh(['alergia']);
    }

    public function delete(string $id): void
    {
        $animalAlergia = Animal_Alergia::findOrFail($id);
        $animalAlergia->delete();
    }
}
