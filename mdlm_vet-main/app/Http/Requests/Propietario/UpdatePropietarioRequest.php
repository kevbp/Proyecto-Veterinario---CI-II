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
        new OA\Property(property: 'vivienda_direccion', type: 'string', nullable: true, example: 'Av. La Molina 123, Lima, Perú'),
        new OA\Property(property: 'vivienda_latitud', type: 'number', format: 'double', nullable: true, example: -12.0773588),
        new OA\Property(property: 'vivienda_longitud', type: 'number', format: 'double', nullable: true, example: -76.9438497),
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
            'vivienda_direccion' => ['nullable', 'string', 'max:500'],
            'vivienda_latitud' => ['nullable', 'numeric', 'between:-90,90', 'required_with:vivienda_longitud'],
            'vivienda_longitud' => ['nullable', 'numeric', 'between:-180,180', 'required_with:vivienda_latitud'],
        ];
    }
}
