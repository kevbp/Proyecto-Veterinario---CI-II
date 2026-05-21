<?php

namespace App\Services\Contracts;

use App\DTOs\Consulta\CreateConsultaDTO;
use App\DTOs\Consulta\UpdateConsultaDTO;
use App\Models\Consulta;
use Illuminate\Database\Eloquent\Collection;

interface ConsultaServiceInterface
{
    public function getAll(): Collection;
    public function getById(string $id): Consulta;
    public function create(CreateConsultaDTO $dto): Consulta;
    public function update(string $id, UpdateConsultaDTO $dto): Consulta;
    public function delete(string $id): void;
}
