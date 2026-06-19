<?php

namespace App\Http\Requests\Raza;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreRazaRequest',
    required: ['nombre', 'codigo', 'peligroso'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Labrador'),
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN001'),
        new OA\Property(property: 'peligroso', type: 'boolean', example: false),
        new OA\Property(property: 'especie_id', type: 'string', example: 'CAN'),
    ],
    type: 'object'
)]
class StoreRazaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'peligroso' => 'required|boolean',
            'especie_id' => 'nullable|string|exists:especies,codigo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El nombre debe tener como maximo 255 caracteres',
            'codigo.required' => 'El codigo es requerido',
            'codigo.max' => 'El codigo debe tener como maximo 255 caracteres',
            'peligroso.required' => 'El peligroso es requerido',
            'peligroso.boolean' => 'El peligroso debe ser un booleano',
            'especie_id.exists' => 'El codigo de la especie no existe',
        ];
    }
}
