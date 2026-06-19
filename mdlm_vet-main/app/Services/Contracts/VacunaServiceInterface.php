<?php

namespace App\Services\Contracts;

use App\Models\VacunaAnimal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\DTOs\VacunaAnimal\CreateVacunaDTO;
use App\DTOs\VacunaAnimal\UpdateVacunaDTO;

interface VacunaServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;
    public function getById(string $id): VacunaAnimal;
    public function create(CreateVacunaDTO $dto): VacunaAnimal;
    public function update(UpdateVacunaDTO $dto, string $id): VacunaAnimal;
    public function delete(string $id): void;
}
