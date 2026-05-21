<?php

namespace App\Services\Contracts;

use App\DTOs\Receta\CreateRecetaDTO;
use App\DTOs\Receta\UpdateRecetaDTO;
use App\Models\Receta;
use Illuminate\Database\Eloquent\Collection;

interface RecetaServiceInterface
{
    public function getAll(): Collection;

    public function getById(string $id): ?Receta;

    public function create(CreateRecetaDTO $dto): Receta;

    public function update(string $id, UpdateRecetaDTO $dto): Receta;

    public function delete(string $id): void;
}