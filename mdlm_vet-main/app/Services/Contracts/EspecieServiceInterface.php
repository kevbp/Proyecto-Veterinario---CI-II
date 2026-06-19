<?php

namespace App\Services\Contracts;

use App\DTOs\Especie\CreateEspecieDTO;
use App\DTOs\Especie\UpdateEspecieDTO;
use App\Models\Especie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface EspecieServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?Especie;

    public function getByCodigo(string $codigo): ?Especie;

    public function getByNombre(string $nombre): ?Especie;

    public function create(CreateEspecieDTO $dto): Especie;

    public function update(string $id, UpdateEspecieDTO $dto): Especie;

    public function delete(string $id): void;
}
