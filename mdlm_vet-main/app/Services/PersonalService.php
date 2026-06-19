<?php

namespace App\Services;

use App\Models\Personal;
use App\DTOs\Personal\CreatePersonalDTO;
use App\DTOs\Personal\UpdatePersonalDTO;
use App\Services\Contracts\PersonalServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PersonalService implements PersonalServiceInterface
{
    public function getAll(): LengthAwarePaginator|Collection|array
    {
        return Personal::with(['tipoDocumento', 'user'])->paginate(25);
    }

    public function getById(string $id): ?Personal
    {
        return Personal::with(['tipoDocumento', 'user'])->findOrFail($id);
    }

    /**
     * Crea el registro de personal en la BD local.
     *
     * La vinculación con el SSO se realiza automáticamente cuando el
     * funcionario inicia sesión vía SSO y el listener VincularUsuarioAlSSO
     * encuentra coincidencia por DNI.
     */
    public function create(CreatePersonalDTO $dto): Personal
    {
        $data = $dto->toArray();

        return Personal::create($data);
    }

    public function update(string $id, UpdatePersonalDTO $dto): Personal
    {
        $personal = $this->getById($id);
        $personal->update($dto->toArray());
        return $personal->fresh(['tipoDocumento', 'user']);
    }

    public function delete(string $id): void
    {
        $personal = $this->getById($id);
        $personal->delete();
    }
}
