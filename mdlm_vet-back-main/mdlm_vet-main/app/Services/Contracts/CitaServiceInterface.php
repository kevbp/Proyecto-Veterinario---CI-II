<?php

namespace App\Services\Contracts;

use App\Models\Cita;
use App\DTOs\Cita\CreateCitaDTO;
use App\DTOs\Cita\UpdateCitaDTO;

interface CitaServiceInterface
{
    public function getAll(): array;
    public function getById(string $id): ?Cita;
    public function create(CreateCitaDTO $dto): Cita;
    public function update(string $id, UpdateCitaDTO $dto): Cita;
    public function delete(string $id): void;
}
