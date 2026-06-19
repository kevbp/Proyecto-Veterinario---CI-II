<?php

namespace App\Services;

use App\Models\TipoExamen;
use App\DTOs\TipoExamen\CreateTipoExamenDTO;
use App\DTOs\TipoExamen\UpdateTipoExamenDTO;
use App\Services\Contracts\TipoExamenServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TipoExamenService implements TipoExamenServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return TipoExamen::paginate(25);
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
