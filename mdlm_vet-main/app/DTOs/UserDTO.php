<?php

namespace App\DTOs;

use App\Models\User;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserDTO',
    title: 'UserDTO',
    description: 'DTO para usuarios',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'example@example.com'),
        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'), example: ['admin', 'user']),
        new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'string'), example: ['crear mascotas', 'ver mascotas']),
        new OA\Property(property: 'propietario_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', nullable: true),
    ]
)]
readonly class UserDTO
{
    /**
     * @param array<string> $roles
     * @param array<string> $permissions
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $roles,
        public array $permissions,
        public ?string $propietario_id = null,
        public ?string $personal_id = null,
    ) {}

    public static function fromModel(User $user): self
    {
        // Obtener permisos: primero permisos directos, luego permisos de roles
        $permissionNames = collect();
        
        // Cargar los roles con sus permisos
        $user->loadMissing('roles.permissions', 'permissions');
        
        // Agregar permisos directos
        if ($user->permissions) {
            $permissionNames = $permissionNames->merge($user->permissions->pluck('name'));
        }
        
        // Agregar permisos de los roles
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if ($role->permissions) {
                    $permissionNames = $permissionNames->merge($role->permissions->pluck('name'));
                }
            }
        }
        
        // Eliminar duplicados y ordenar
        $permissionNames = $permissionNames->unique()->sort()->values()->toArray();
        
        return new self(
            id: (string) $user->id,
            name: $user->name,
            email: $user->email,
            roles: $user->getRoleNames()->toArray(),
            permissions: $permissionNames,
            propietario_id: $user->propietario?->id,
            personal_id: $user->personal?->id,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'propietario_id' => $this->propietario_id,
            'personal_id' => $this->personal_id,
        ];
    }
}
