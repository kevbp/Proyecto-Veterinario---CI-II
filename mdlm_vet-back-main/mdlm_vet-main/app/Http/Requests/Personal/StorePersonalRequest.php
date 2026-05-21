<?php

namespace App\Http\Requests\Personal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePersonalRequest',
    required: ['tipo_doc_id', 'nro_doc', 'nombre', 'paterno', 'email', 'rol_sistema'],
    properties: [
        new OA\Property(property: 'tipo_doc_id', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'string', example: '12345678'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'juan@example.com'),
        new OA\Property(property: 'celular', type: 'string', example: '123456789'),
        new OA\Property(property: 'especialidad', type: 'string', example: 'Veterinario'),
        new OA\Property(property: 'rol_sistema', type: 'string', example: 'veterinario'),
    ]
)]
class StorePersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_doc_id' => 'required|exists:tipo_documentos,codigo',
            'nro_doc' => 'required|string|unique:personal,nro_doc|max:20',
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'nullable|string|max:100',
            'email' => 'required|email|unique:personal,email',
            'celular' => 'nullable|numeric|max_digits:15',
            'especialidad' => 'nullable|string|max:100',
            'rol_sistema' => 'required|string|in:admin,gestor,recepcionista,veterinario',
        ];
    }
}
