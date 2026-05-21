<?php

namespace App\Services;

use App\Models\Cita;
use App\DTOs\Cita\CreateCitaDTO;
use App\DTOs\Cita\UpdateCitaDTO;
use App\Services\Contracts\CitaServiceInterface;

class CitaService implements CitaServiceInterface
{
    public function getAll(): array
    {
        return Cita::all()->toArray();
    }

    public function getById(string $id): ?Cita
    {
        return Cita::find($id);
    }

    public function create(CreateCitaDTO $dto): Cita
    {
        return Cita::create($dto->toArray());
    }

    public function update(string $id, UpdateCitaDTO $dto): Cita
    {
        $cita = $this->getById($id);
        $cita->update($dto->toArray());
        return $cita->fresh();
    }

    public function delete(string $id): void
    {
        $cita = $this->getById($id);
        $cita->delete();
    }
}