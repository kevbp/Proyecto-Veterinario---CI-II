<?php

namespace App\Services\Contracts;

use App\DTOs\Campania\CreateCampaniaDTO;
use App\DTOs\Campania\UpdateCampaniaDTO;
use App\DTOs\Campania\FinalizarCampaniaDTO;
use App\Models\Campania;
use Illuminate\Database\Eloquent\Collection;

interface CampaniaServiceInterface
{
    public function getAll(): Collection;

    public function obtenerCampaniasActivas(): Collection;

    public function getById(string $id): Campania;

    public function create(CreateCampaniaDTO $dto): Campania;

    public function update(string $id, UpdateCampaniaDTO $dto): Campania;

    public function delete(string $id): void;

    public function iniciarCampania(string $id): Campania;

    public function cancelarCampania(string $id): Campania;

    public function finalizarCampania(string $id, FinalizarCampaniaDTO $dto): Campania;

    public function obtenerEstadisticas(string $id): array;
}
