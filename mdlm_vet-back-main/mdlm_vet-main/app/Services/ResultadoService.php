<?php

namespace App\Services;

use App\Models\Resultado;
use App\DTOs\Resultado\CreateResultadoDTO;
use App\DTOs\Resultado\UpdateResultadoDTO;
use App\Services\Contracts\ResultadoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ResultadoService implements ResultadoServiceInterface
{
    public function getAll(): Collection
    {
        return Resultado::all();
    }

    public function getById(string $id): Resultado
    {
        return Resultado::findOrFail($id);
    }

    public function create(CreateResultadoDTO $dto): Resultado
    {
        return Resultado::create($dto->toArray());
    }

    public function update(string $id, UpdateResultadoDTO $dto): Resultado
    {
        $resultado = Resultado::findOrFail($id);
        $resultado->update($dto->toArray());

        return $resultado->fresh();
    }

    public function delete(string $id): void
    {
        $resultado = Resultado::findOrFail($id);
        $resultado->delete();
    }
}
