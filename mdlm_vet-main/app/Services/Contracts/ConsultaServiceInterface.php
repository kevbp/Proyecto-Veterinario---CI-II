<?php

namespace App\Services\Contracts;

use App\DTOs\Consulta\CreateConsultaDTO;
use App\DTOs\Consulta\UpdateConsultaDTO;
use App\Models\Consulta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface ConsultaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;
    public function getById(string $id): Consulta;
    public function create(CreateConsultaDTO $dto): Consulta;
    public function update(string $id, UpdateConsultaDTO $dto): Consulta;
    public function delete(string $id): void;
}
