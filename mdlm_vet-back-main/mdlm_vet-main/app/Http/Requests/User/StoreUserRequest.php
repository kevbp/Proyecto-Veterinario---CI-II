<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreUserRequest',
    required: ['name', 'email', 'password', 'password_confirmation', 'role'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'María López'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@veterinaria.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: '12345678'),
        new OA\Property(property: 'role', type: 'string', enum: ['gestor', 'recepcionista', 'veterinario'], example: 'veterinario'),
    ],
    type: 'object'
)]
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:gestor,recepcionista,veterinario'],
        ];
    }
}
