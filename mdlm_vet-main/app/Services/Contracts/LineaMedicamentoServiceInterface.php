<?php

namespace App\Services\Contracts;

use App\DTOs\LineaMedicamento\CreateLineaMedicamentoDTO;
use App\DTOs\LineaMedicamento\UpdateLineaMedicamentoDTO;
use App\Models\LineaMedicamento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface LineaMedicamentoServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?LineaMedicamento;

    public function create(CreateLineaMedicamentoDTO $dto): LineaMedicamento;

    public function update(string $id, UpdateLineaMedicamentoDTO $dto): LineaMedicamento;

    public function delete(string $id): void;

    public function getByRecetaId(string $recetaId): Collection;
}
