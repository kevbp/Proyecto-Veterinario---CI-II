<?php

namespace App\Services;

use App\DTOs\Animal_Alergia\CreateAnimalAlergiaDTO;
use App\DTOs\Animal_Alergia\UpdateAnimalAlergiaDTO;
use App\Models\Animal_Alergia;
use App\Models\CatalogoAlergias;
use App\Services\Contracts\AnimalAlergiaServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class AnimalAlergiaService implements AnimalAlergiaServiceInterface
{
    public function getAll(): Collection
    {
        return Animal_Alergia::all();
    }

    public function getById(string $id): ?Animal_Alergia
    {
        return Animal_Alergia::findOrFail($id);
    }

    public function create(CreateAnimalAlergiaDTO $dto): Animal_Alergia
    {
        $data = $dto->toArray();
        $data['alergia_id'] = CatalogoAlergias::where('codigo', $data['alergia_id'])->firstOrFail()->id;

        return Animal_Alergia::create($data);
    }

    public function update(string $id, UpdateAnimalAlergiaDTO $data): Animal_Alergia
    {
        $animalAlergia = Animal_Alergia::findOrFail($id);
        $data = $data->toArray();
        $data['alergia_id'] = CatalogoAlergias::where('codigo', $data['alergia_id'])->firstOrFail()->id;
        $animalAlergia->update($data);

        return $animalAlergia->fresh();
    }

    public function delete(string $id): void
    {
        $animalAlergia = Animal_Alergia::findOrFail($id);
        $animalAlergia->delete();
    }
}
