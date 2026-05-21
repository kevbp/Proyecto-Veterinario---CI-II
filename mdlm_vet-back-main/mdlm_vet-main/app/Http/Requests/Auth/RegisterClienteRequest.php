<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterClienteRequest',
    required: ['token', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'token', type: 'string', example: 'abc123token...'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: '12345678'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: '12345678'),
    ],
    type: 'object'
)]
class RegisterClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'exists:propietarios,invitation_token'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
