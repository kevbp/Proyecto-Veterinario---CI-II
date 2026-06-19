<?php

namespace App\Services\Contracts;

use App\DTOs\Personal\CreatePersonalDTO;
use App\DTOs\Personal\UpdatePersonalDTO;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PersonalServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;

    public function getById(string $id): ?Personal;

    public function create(CreatePersonalDTO $dto): Personal;

    public function update(string $id, UpdatePersonalDTO $dto): Personal;

    public function delete(string $id): void;
}
