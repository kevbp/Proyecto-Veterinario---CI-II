<?php

namespace App\Services;

use App\DTOs\TipoExamen\CreateTipoExamenDTO;
use App\DTOs\TipoExamen\UpdateTipoExamenDTO;
use App\Models\TipoExamen;
use App\Services\Contracts\TipoExamenServiceInterface;

class TipoExamenService implements TipoExamenServiceInterface
{
    public function getAll()
    {
        return TipoExamen::all();
    }

    public function getById(string $id): ?TipoExamen
    {
        return TipoExamen::findOrFail($id);
    }

    public function create(CreateTipoExamenDTO $dto): TipoExamen
    {
        $tipoExamen = TipoExamen::create($dto->toArray());

        return $tipoExamen;
    }

    public function update(string $id, UpdateTipoExamenDTO $dto): TipoExamen
    {
        $tipoExamen = $this->getById($id);
        $tipoExamen->update($dto->toArray());

        return $tipoExamen->fresh();
    }

    public function delete(string $id): void
    {
        $tipoExamen = $this->getById($id);
        $tipoExamen->delete();
    }
}
