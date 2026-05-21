<?php

namespace App\Services;

use App\DTOs\EsquemaVacuna\CreateEsquemaVacunaDTO;
use App\DTOs\EsquemaVacuna\UpdateEsquemaVacunaDTO;
use App\Models\EsquemaVacuna;
use App\Services\Contracts\EsquemaVacunaServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class EsquemaVacunaService implements EsquemaVacunaServiceInterface
{
    public function getAll(): Collection
    {
        return EsquemaVacuna::all();
    }

    public function getById(string $id): ?EsquemaVacuna
    {
        return EsquemaVacuna::find($id);
    }

    public function create(CreateEsquemaVacunaDTO $dto): EsquemaVacuna
    {
        return EsquemaVacuna::create($dto->toArray());
    }

    public function update(string $id, UpdateEsquemaVacunaDTO $dto): EsquemaVacuna
    {
        $esquema = EsquemaVacuna::findOrFail($id);
        $esquema->update($dto->toArray());

        return $esquema->fresh();
    }

    public function delete(string $id): void
    {
        $esquema = EsquemaVacuna::findOrFail($id);
        $esquema->delete();
    }
}
