<?php

namespace App\DTOs\User;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateUserDTO',
    required: ['name', 'email', 'password', 'role'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Juan'),
        new OA\Property(property: 'email', type: 'string', example: 'example@example.com'),
        new OA\Property(property: 'password', type: 'string', example: 'password'),
        new OA\Property(property: 'role', type: 'string', example: 'admin')
    ],
    type: 'object'
)]
class CreateUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
