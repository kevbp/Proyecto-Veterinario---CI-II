<?php

namespace App\Services;

use App\DTOs\Raza\CreateRazaDTO;
use App\DTOs\Raza\UpdateRazaDTO;
use App\Models\Especie;
use App\Models\Raza;
use App\Services\Contracts\RazaServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RazaService implements RazaServiceInterface
{
    public function getAll(): Collection
    {
        return Raza::all();
    }

    public function getById(string $id): ?Raza
    {
        return Raza::findOrFail($id);
    }

    public function getByEspecieId(string $codigo): Collection
    {
        $especie = Especie::where('codigo', $codigo)->firstOrFail();
        return Raza::where('especie_id', $especie->id)->get();
    }

    public function create(CreateRazaDTO $dto): Raza
    {
        $data = $dto->toArray();
        $data['especie_id'] = Especie::where('codigo', $data['especie_id'])->firstOrFail()->id;

        return Raza::create($data);
    }

    public function update(string $id, UpdateRazaDTO $dto): Raza
    {
        $raza = Raza::findOrFail($id);
        $data = $dto->toArray();
        $data['especie_id'] = Especie::where('codigo', $data['especie_id'])->firstOrFail()->id;
        $raza->update($data);

        return $raza->fresh();
    }

    public function delete(string $id): void
    {
        $raza = Raza::findOrFail($id);
        $raza->delete();
    }
}
