<?php

namespace App\Services\Contracts;

use App\DTOs\Animal_Alergia\CreateAnimalAlergiaDTO;
use App\DTOs\Animal_Alergia\UpdateAnimalAlergiaDTO;
use App\Models\Animal_Alergia;
use Illuminate\Database\Eloquent\Collection;

interface AnimalAlergiaServiceInterface
{
    public function getAll(): Collection;

    public function getById(string $id): ?Animal_Alergia;

    public function create(CreateAnimalAlergiaDTO $dto): Animal_Alergia;

    public function update(string $id, UpdateAnimalAlergiaDTO $dto): Animal_Alergia;

    public function delete(string $id): void;
}
