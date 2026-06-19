<?php

namespace App\DTOs\User;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateUserDTO',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Juan'),
        new OA\Property(property: 'email', type: 'string', example: 'example@example.com'),
        new OA\Property(property: 'password', type: 'string', example: 'password'),
        new OA\Property(property: 'role', type: 'string', example: 'admin')
    ],
    type: 'object'
)]
class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $role = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            role: $data['role'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ], fn($value) => !is_null($value));
    }
}
