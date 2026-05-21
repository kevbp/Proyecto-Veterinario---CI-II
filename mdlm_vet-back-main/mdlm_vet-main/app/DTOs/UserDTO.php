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
    ]
)]
readonly class UserDTO
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $roles,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: (string) $user->id,
            name: $user->name,
            email: $user->email,
            roles: $user->getRoleNames()->toArray(),
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
        ];
    }
}
