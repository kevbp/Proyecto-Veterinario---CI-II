<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CrudServiceInterface
{
    /**
     * @return array<int, object>
     */
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?object;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): object;

    /**
     * @param array<string, mixed> $data
     */
    public function update(string $id, array $data): object;

    public function delete(string $id): bool;
}
