<?php

namespace App\Services\Contracts;

use App\DTOs\Medicamento\CreateMedicamentoDTO;
use App\DTOs\Medicamento\UpdateMedicamentoDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Medicamento;

interface MedicamentoServiceInterface
{
    public function getAll(): Collection;

    public function getById(string $id): ?Medicamento;

    public function create(CreateMedicamentoDTO $dto): Medicamento;

    public function update(string $id, UpdateMedicamentoDTO $dto): Medicamento;

    public function delete(string $id): void;
}
