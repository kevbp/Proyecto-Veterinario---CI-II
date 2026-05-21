<?php

namespace App\Services\Contracts;

use App\DTOs\Personal\CreatePersonalDTO;
use App\DTOs\Personal\UpdatePersonalDTO;
use App\Models\Personal;

interface PersonalServiceInterface
{
    public function getAll();

    public function getById(string $id): ?Personal;

    public function create(CreatePersonalDTO $dto): Personal;

    public function update(string $id, UpdatePersonalDTO $dto): Personal;

    public function delete(string $id): void;

    public function resendInvitation(string $id): Personal;
}
