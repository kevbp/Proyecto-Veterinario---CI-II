<?php

namespace App\Services;

use App\Models\Instrumento;
use App\DTOs\InstrumentoDTO;
use App\Services\Contracts\InstrumentoServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InstrumentoService implements InstrumentoServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Instrumento::paginate(25);
    }

    public function getById(string $id): ?InstrumentoDTO
    {
        $instrumento = Instrumento::find($id);

        return $instrumento ? InstrumentoDTO::fromModel($instrumento) : null;
    }

    public function create(array $data): InstrumentoDTO
    {
        $instrumento = Instrumento::create($data);

        return InstrumentoDTO::fromModel($instrumento);
    }

    public function update(string $id, array $data): InstrumentoDTO
    {
        $instrumento = Instrumento::findOrFail($id);
        $instrumento->update($data);

        return InstrumentoDTO::fromModel($instrumento->fresh());
    }

    public function delete(string $id): bool
    {
        $instrumento = Instrumento::findOrFail($id);

        return $instrumento->delete();
    }
}
