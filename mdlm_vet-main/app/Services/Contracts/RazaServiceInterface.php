<?php

namespace App\Services\Contracts;

use App\DTOs\Raza\CreateRazaDTO;
use App\DTOs\Raza\UpdateRazaDTO;
use App\Models\Raza;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface RazaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?Raza;

    public function getByEspecieId(string $codigo): Collection;

    public function create(CreateRazaDTO $dto): Raza;

    public function update(string $id, UpdateRazaDTO $dto): Raza;

    public function delete(string $id): void;
}
