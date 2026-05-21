<?php

namespace App\Services\Contracts;

use App\DTOs\TipoExamen\CreateTipoExamenDTO;
use App\DTOs\TipoExamen\UpdateTipoExamenDTO;
use App\Models\TipoExamen;

interface TipoExamenServiceInterface
{
    public function getAll();

    public function getById(string $id): ?TipoExamen;

    public function create(CreateTipoExamenDTO $dto): TipoExamen;

    public function update(string $id, UpdateTipoExamenDTO $dto): TipoExamen;

    public function delete(string $id): void;
}
