<?php

namespace App\Services\Contracts;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAll(): Collection;
    public function findById(string $id): User;
    public function create(CreateUserDTO $dto, User $creator): User;
    public function update(string $id, UpdateUserDTO $dto, User $editor): User;
    public function delete(string $id, User $deleter): bool;
    public function getAssignableRoles(User $user): array;
}
