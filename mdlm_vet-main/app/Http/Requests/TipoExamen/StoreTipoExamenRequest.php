<?php

namespace App\Http\Requests\TipoExamen;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreTipoExamenRequest',
    required: ['codigo', 'nombre', 'categoria', 'precio_ref'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'EXA-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Examen de Sangre'),
        new OA\Property(property: 'categoria', type: 'string', example: 'Laboratorio'),
        new OA\Property(property: 'precio_ref', type: 'number', format: 'float', example: 100.00),
    ]
)]
class StoreTipoExamenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:10|unique:tipo_examenes,codigo',
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio_ref' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio',
            'codigo.max' => 'El código debe tener máximo 10 caracteres',
            'codigo.unique' => 'El código ya existe',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre debe tener máximo 255 caracteres',
            'categoria.required' => 'La categoría es obligatoria',
            'categoria.max' => 'La categoría debe tener máximo 255 caracteres',
            'precio_ref.required' => 'El precio es obligatorio',
            'precio_ref.numeric' => 'El precio debe ser un número',
            'precio_ref.min' => 'El precio debe ser mayor o igual a 0',
        ];
    }
}
