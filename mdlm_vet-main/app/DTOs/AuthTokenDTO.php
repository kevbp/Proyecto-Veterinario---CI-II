<?php

namespace App\DTOs;

use App\Models\User;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthTokenDTO',
    properties: [
        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAi...'),
        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
        new OA\Property(property: 'user', ref: '#/components/schemas/UserDTO'),
    ]
)]
readonly class AuthTokenDTO
{
    public function __construct(
        public string $access_token,
        public string $token_type,
        public int $expires_in,
        public UserDTO $user,
    ) {}

    public static function fromToken(string $token, User $user): self
    {
        return new self(
            access_token: $token,
            token_type: 'bearer',
            expires_in: auth('api')->factory()->getTTL() * 60,
            user: UserDTO::fromModel($user),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'user' => $this->user->toArray(),
        ];
    }
}
