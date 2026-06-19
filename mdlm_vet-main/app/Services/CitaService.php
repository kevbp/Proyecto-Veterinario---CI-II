<?php

namespace App\Services;

use App\Models\Cita;
use App\DTOs\Cita\CreateCitaDTO;
use App\DTOs\Cita\UpdateCitaDTO;
use App\Services\Contracts\CitaServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CitaService implements CitaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Cita::paginate(25);
    }

    public function getById(string $id): ?Cita
    {
        return Cita::findOrFail($id);
    }

    public function create(CreateCitaDTO $dto): Cita
    {
        return Cita::create($dto->toArray());
    }

    public function update(string $id, UpdateCitaDTO $dto): Cita
    {
        $cita = Cita::findOrFail($id);
        $cita->update($dto->toArray());
        return $cita->fresh();
    }

    public function delete(string $id): void
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
    }
}
