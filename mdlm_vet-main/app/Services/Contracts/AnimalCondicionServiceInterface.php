<?php

namespace App\Services\Contracts;

use App\DTOs\Animal_Condicion\CreateAnimalCondicionDTO;
use App\DTOs\Animal_Condicion\UpdateAnimalCondicionDTO;
use App\Models\Animal_Condicion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface AnimalCondicionServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?Animal_Condicion;

    public function create(CreateAnimalCondicionDTO $dto): Animal_Condicion;

    public function update(string $id, UpdateAnimalCondicionDTO $dto): Animal_Condicion;

    public function delete(string $id): void;
}
