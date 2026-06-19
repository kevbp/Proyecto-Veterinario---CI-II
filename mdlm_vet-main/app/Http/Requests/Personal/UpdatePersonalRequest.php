<?php

namespace App\Http\Requests\Personal;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePersonalRequest',
    properties: [
        new OA\Property(property: 'tipo_doc_id', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: 12345678),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'juan@example.com'),
        new OA\Property(property: 'celular', type: 'string', example: '123456789'),
        new OA\Property(property: 'especialidad', type: 'string', example: 'Veterinario'),
        new OA\Property(property: 'rol_sistema', type: 'string', example: 'veterinario'),
    ]
)]
class UpdatePersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('personal') ? $this->route('personal')->id : $this->route('id');

        return [
            'tipo_doc_id' => 'sometimes|exists:tipo_documentos,codigo',
            'nro_doc' => 'sometimes|integer',
            'nombre' => 'sometimes|string|max:100',
            'paterno' => 'sometimes|string|max:100',
            'materno' => 'nullable|string|max:100',
            'email' => 'sometimes|email|unique:personal,email,' . $id,
            'celular' => 'nullable|string|max:50',
            'especialidad' => 'nullable|string|max:100',
            'rol_sistema' => 'sometimes|string|in:admin,gestor,recepcionista,veterinario',
        ];
    }
}
