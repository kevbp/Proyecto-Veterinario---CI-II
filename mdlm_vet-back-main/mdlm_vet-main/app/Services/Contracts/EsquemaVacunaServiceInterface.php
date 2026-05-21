<?php

namespace App\Services\Contracts;

use App\DTOs\EsquemaVacuna\CreateEsquemaVacunaDTO;
use App\DTOs\EsquemaVacuna\UpdateEsquemaVacunaDTO;
use App\Models\EsquemaVacuna;
use Illuminate\Database\Eloquent\Collection;

interface EsquemaVacunaServiceInterface
{
    public function getAll(): Collection|array;

    public function getById(string $id): ?EsquemaVacuna;

    public function create(CreateEsquemaVacunaDTO $dto): EsquemaVacuna;

    public function update(string $id, UpdateEsquemaVacunaDTO $dto): EsquemaVacuna;

    public function delete(string $id): void;
}
