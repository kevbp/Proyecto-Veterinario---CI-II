<?php

namespace App\Http\Requests\Especie;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreEspecieRequest',
    title: 'StoreEspecieRequest',
    description: 'Request para crear una especie',
    required: ['codigo', 'nombre'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Canino'),
    ]
)]
class StoreEspecieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:3|unique:especies,codigo',
            'nombre' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio',
            'codigo.max' => 'El código debe tener máximo 3 caracteres',
            'codigo.unique' => 'El código ya existe',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre debe tener máximo 255 caracteres',
        ];
    }
}
