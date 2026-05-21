<?php

namespace App\Http\Requests\CatalogoCondiciones;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCatalogoCondicionesRequest',
    required: ['nombre', 'codigo'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Alergia a la penicilina'),
        new OA\Property(property: 'codigo', type: 'string', example: 'Alergia a la penicilina'),
    ]
)]
class StoreCatalogoCondicionesRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'codigo.required' => 'El codigo es requerido',
        ];
    }
}
