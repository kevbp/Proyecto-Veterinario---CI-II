<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateUserRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'María López'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@veterinaria.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: '12345678'),
        new OA\Property(property: 'role', type: 'string', enum: ['gestor', 'recepcionista', 'veterinario'], example: 'veterinario'),
    ],
    type: 'object'
)]
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('usuario');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $userId],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', 'string', 'in:gestor,recepcionista,veterinario'],
        ];
    }
}
