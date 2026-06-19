<?php

namespace App\Services\Contracts;

use App\DTOs\Propietario\CreatePropietarioDTO;
use App\DTOs\Propietario\UpdatePropietarioDTO;
use App\Models\Propietario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface PropietarioServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;
    public function findById(string $id): Propietario;
    public function create(CreatePropietarioDTO $dto): Propietario;
    public function update(string $id, UpdatePropietarioDTO $dto): Propietario;
    public function delete(string $id): bool;
    public function obtenerDireccion(string $id): ?array;
    public function getSsoRegistroUrl(): string;
}
