<?php

namespace App\Http\Requests\Propietario;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePropietarioRequest',
    properties: [
        new OA\Property(property: 'tipo_doc', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'integer'),
        new OA\Property(property: 'nombre', type: 'string'),
        new OA\Property(property: 'paterno', type: 'string'),
        new OA\Property(property: 'materno', type: 'string'),
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'celular', type: 'integer'),
        new OA\Property(property: 'nro_emergencia', type: 'integer'),
    ],
    type: 'object'
)]
class UpdatePropietarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('propietario');
        return [
            'tipo_doc' => ['sometimes', 'string', 'exists:tipo_documentos,codigo'],
            'nro_doc' => ['sometimes', 'integer', 'unique:propietarios,nro_doc,' . $id],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'paterno' => ['sometimes', 'string', 'max:255'],
            'materno' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'celular' => ['nullable', 'integer', 'max_digits:15'],
            'nro_emergencia' => ['nullable', 'integer', 'max_digits:15'],
        ];
    }
}
