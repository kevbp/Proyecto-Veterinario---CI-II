<?php

namespace App\Services\Contracts;

use App\DTOs\Animal\CreateAnimalDTO;
use App\DTOs\Animal\UpdateAnimalDTO;
use App\Models\Animal;
use App\Models\Propietario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AnimalServiceInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator|Collection|array;

    public function getById(string $id): Animal;

    public function getPropietarioByAnimalId(string $id): Propietario;

    public function create(CreateAnimalDTO $dto): Animal;

    public function update(string $id, UpdateAnimalDTO $dto): Animal;

    public function delete(string $id): void;

    public function registrarFallecimiento(string $id): Animal;
}
