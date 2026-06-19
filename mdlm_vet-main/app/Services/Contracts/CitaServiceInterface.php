<?php

namespace App\Services\Contracts;

use App\Models\Cita;
use App\DTOs\Cita\CreateCitaDTO;
use App\DTOs\Cita\UpdateCitaDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CitaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;
    public function getById(string $id): ?Cita;
    public function create(CreateCitaDTO $dto): Cita;
    public function update(string $id, UpdateCitaDTO $dto): Cita;
    public function delete(string $id): void;
}
