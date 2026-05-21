<?php

namespace App\Services;

use App\Models\LineaMedicamento;
use App\DTOs\LineaMedicamento\CreateLineaMedicamentoDTO;
use App\DTOs\LineaMedicamento\UpdateLineaMedicamentoDTO;
use App\Services\Contracts\LineaMedicamentoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class LineaMedicamentoService implements LineaMedicamentoServiceInterface
{
    public function getAll(): Collection
    {
        return LineaMedicamento::all();
    }

    public function getById(string $id): ?LineaMedicamento
    {
        return LineaMedicamento::findOrFail($id);
    }

    public function create(CreateLineaMedicamentoDTO $data): LineaMedicamento
    {
        return LineaMedicamento::create($data->toArray());
    }

    public function update(string $id, UpdateLineaMedicamentoDTO $data): LineaMedicamento
    {
        $linea = LineaMedicamento::findOrFail($id);
        $linea->update($data->toArray());

        return LineaMedicamento::fresh($id);
    }

    public function delete(string $id): void
    {
        $linea = LineaMedicamento::findOrFail($id);

        $linea->delete();
    }

    public function getByRecetaId(string $recetaId): Collection
    {
        return LineaMedicamento::where('receta_id', $recetaId)->get();
    }
}
