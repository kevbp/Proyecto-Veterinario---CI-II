<?php

namespace App\Services\Contracts;

use App\Models\Desparasitacion;
use App\DTOs\Desparasitacion\CreateDesparasitacionDTO;
use App\DTOs\Desparasitacion\UpdateDesparasitacionDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface DesparasitacionServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array;
    public function getById(string $id): ?Desparasitacion;
    public function create(CreateDesparasitacionDTO $dto): Desparasitacion;
    public function update(UpdateDesparasitacionDTO $dto, string $id): Desparasitacion;
    public function delete(string $id): void;
}
