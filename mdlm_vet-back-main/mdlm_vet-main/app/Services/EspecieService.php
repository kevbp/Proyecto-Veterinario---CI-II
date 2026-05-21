<?php

namespace App\Services;

use App\Models\Especie;
use App\DTOs\Especie\CreateEspecieDTO;
use App\DTOs\Especie\UpdateEspecieDTO;
use App\Services\Contracts\EspecieServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class EspecieService implements EspecieServiceInterface
{
    public function getAll(): array
    {
        return Especie::all()->toArray();
    }

    public function getById(string $id): ?Especie
    {
        return Especie::find($id);
    }

    public function getByCodigo(string $codigo): ?Especie
    {
        return Especie::where('codigo', $codigo)->first();
    }

    public function getByNombre(string $nombre): ?Especie
    {
        return Especie::where('nombre', $nombre)->first();
    }

    public function create(CreateEspecieDTO $dto): Especie
    {
        return Especie::create($dto->toArray());
    }

    public function update(string $id, UpdateEspecieDTO $dto): Especie
    {
        $especie = $this->getById($id);
        $especie->update($dto->toArray());
        return $especie->fresh();
    }

    public function delete(string $id): void
    {
        $especie = $this->getById($id);
        $especie->delete();
    }
}
