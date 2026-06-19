<?php

namespace App\Services\Contracts;

use App\Models\Resultado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\DTOs\Resultado\CreateResultadoDTO;
use App\DTOs\Resultado\UpdateResultadoDTO;

interface ResultadoServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): Resultado;

    public function create(CreateResultadoDTO $dto): \App\Models\Resultado;

    public function update(string $id, UpdateResultadoDTO $dto):Resultado;

    public function delete(string $id): void;
}
