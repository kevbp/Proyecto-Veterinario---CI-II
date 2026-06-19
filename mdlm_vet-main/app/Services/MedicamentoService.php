<?php

namespace App\Services;

use App\Models\Medicamento;
use App\DTOs\Medicamento\CreateMedicamentoDTO;
use App\DTOs\Medicamento\UpdateMedicamentoDTO;
use App\Services\Contracts\MedicamentoServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Exception;
use PhpParser\Lexer\TokenEmulator\VoidCastEmulator;

class MedicamentoService implements MedicamentoServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Medicamento::paginate(25);
    }

    public function getById(string $id): ?Medicamento
    {
        return Medicamento::findOrFail($id);
    }

    public function create(CreateMedicamentoDTO $dto): Medicamento
    {
        return Medicamento::create($dto->toArray());
    }

    public function update(string $id, UpdateMedicamentoDTO $dto): Medicamento
    {
        $medicamento = Medicamento::findOrFail($id);
        $medicamento->update($dto->toArray());

        return Medicamento::findOrFail($id);
    }

    public function delete(string $id): void
    {
        $medicamento = Medicamento::findOrFail($id);

        $medicamento->delete();
    }
}
