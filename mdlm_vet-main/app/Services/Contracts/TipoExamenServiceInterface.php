<?php

namespace App\Services\Contracts;

use App\Models\TipoExamen;
use App\DTOs\TipoExamen\CreateTipoExamenDTO;
use App\DTOs\TipoExamen\UpdateTipoExamenDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TipoExamenServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?TipoExamen;

    public function create(CreateTipoExamenDTO $dto): TipoExamen;

    public function update(string $id, UpdateTipoExamenDTO $dto): TipoExamen;

    public function delete(string $id): void;
}
