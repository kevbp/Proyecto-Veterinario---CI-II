<?php

namespace App\Http\Requests\Propietario;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePropietarioRequest',
    required: ['tipo_doc', 'nro_doc', 'nombre', 'paterno', 'email'],
    properties: [
        new OA\Property(property: 'tipo_doc', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: 74082478),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'juan.perez@example.com'),
        new OA\Property(property: 'celular', type: 'integer', example: 999888777),
        new OA\Property(property: 'nro_emergencia', type: 'integer', example: 999888666),
    ],
    type: 'object'
)]
class StorePropietarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_doc' => ['required', 'string', 'exists:tipo_documentos,codigo'],
            'nro_doc' => ['required', 'integer', 'unique:propietarios,nro_doc'],
            'nombre' => ['required', 'string', 'max:255'],
            'paterno' => ['required', 'string', 'max:255'],
            'materno' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:propietarios,email'],
            'celular' => ['nullable', 'integer', 'max_digits:15'],
            'nro_emergencia' => ['nullable', 'integer', 'max_digits:15'],
        ];
    }
}
