<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService implements UserServiceInterface
{
    /**
     * Jerarquía de roles: cuanto menor el número, mayor la jerarquía.
     */
    private const ROLE_HIERARCHY = [
        'admin' => 1,
        'gestor' => 2,
        'veterinario' => 3,
        'recepcionista' => 3,
        'propietario' => 4,
    ];

    public function getAll(): Collection
    {
        return User::with('roles')->latest()->get();
    }

    public function findById(string $id): User
    {
        return User::with('roles')->findOrFail($id);
    }

    public function create(CreateUserDTO $dto, User $creator): User
    {
        $this->assertCanAssignRole($creator, $dto->role);

        $user = User::create($dto->toArray());
        $user->assignRole($dto->role);

        return $user->load('roles');
    }

    public function update(string $id, UpdateUserDTO $dto, User $editor): User
    {
        $user = $this->findById($id);

        // No puede editar a alguien de igual o mayor jerarquía (salvo a sí mismo)
        if ($user->id !== $editor->id) {
            $this->assertCanManageUser($editor, $user);
        }

        $user->update($dto->toArray());

        if ($dto->role) {
            $this->assertCanAssignRole($editor, $dto->role);
            $user->syncRoles([$dto->role]);
        }

        return $user->load('roles');
    }

    public function delete(string $id, User $deleter): bool
    {
        $user = $this->findById($id);

        if ($user->id === $deleter->id) {
            throw new AccessDeniedHttpException('No puedes eliminarte a ti mismo.');
        }

        $this->assertCanManageUser($deleter, $user);

        return $user->delete();
    }

    /**
     * Retorna los roles que el usuario puede asignar según su jerarquía.
     */
    public function getAssignableRoles(User $user): array
    {
        $userLevel = $this->getUserLevel($user);

        return collect(self::ROLE_HIERARCHY)
            ->filter(fn(int $level) => $level > $userLevel)
            ->keys()
            ->values()
            ->toArray();
    }

    // ─── Helpers privados ──────────────────────────────────────

    private function getUserLevel(User $user): int
    {
        $roles = $user->getRoleNames()->toArray();

        if (empty($roles)) {
            return PHP_INT_MAX;
        }

        return collect($roles)
            ->map(fn(string $role) => self::ROLE_HIERARCHY[$role] ?? PHP_INT_MAX)
            ->min();
    }

    private function assertCanAssignRole(User $creator, string $role): void
    {
        $assignable = $this->getAssignableRoles($creator);

        if (!in_array($role, $assignable)) {
            throw new AccessDeniedHttpException(
                "No tienes permiso para asignar el rol '{$role}'. Roles asignables: " . implode(', ', $assignable)
            );
        }
    }

    private function assertCanManageUser(User $manager, User $target): void
    {
        $managerLevel = $this->getUserLevel($manager);
        $targetLevel = $this->getUserLevel($target);

        if ($managerLevel >= $targetLevel) {
            throw new AccessDeniedHttpException(
                'No puedes gestionar a un usuario de igual o mayor jerarquía.'
            );
        }
    }
}
