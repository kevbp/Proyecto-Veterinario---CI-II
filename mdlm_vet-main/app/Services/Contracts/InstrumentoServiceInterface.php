<?php

namespace App\Services\Contracts;

use App\DTOs\InstrumentoDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InstrumentoServiceInterface extends CrudServiceInterface
{
    /**
     * @return array<int, InstrumentoDTO>
     */
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?InstrumentoDTO;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): InstrumentoDTO;

    /**
     * @param array<string, mixed> $data
     */
    public function update(string $id, array $data): InstrumentoDTO;

    public function delete(string $id): bool;
}
