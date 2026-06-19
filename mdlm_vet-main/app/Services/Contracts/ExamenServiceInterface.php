<?php

namespace App\Services\Contracts;

use App\DTOs\Examen\CreateExamenDTO;
use App\DTOs\Examen\UpdateExamenDTO;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface ExamenServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): Examen;

    public function create(CreateExamenDTO $dto): Examen;

    public function update(string $id, UpdateExamenDTO $dto): Examen;

    public function delete(string $id): void;
}
