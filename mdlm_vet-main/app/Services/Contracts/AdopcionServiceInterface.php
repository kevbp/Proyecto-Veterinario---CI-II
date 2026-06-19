<?php

namespace App\Services\Contracts;

use App\Models\Adopcion;
use App\DTOs\Adopcion\EstadisticaCampaniaDTO;
use App\DTOs\Adopcion\EstadisticaFechasDTO;
use App\DTOs\Animal\RegistrarAdopcionDTO;

interface AdopcionServiceInterface
{
    public function registrarAdopcion(RegistrarAdopcionDTO $dto): Adopcion;
    public function getAll(array $filters = []): \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator;
    public function obtenerEstadisticasCampania(EstadisticaCampaniaDTO $dto): array;
    public function obtenerEstadisticasFechas(EstadisticaFechasDTO $dto): array;
}
