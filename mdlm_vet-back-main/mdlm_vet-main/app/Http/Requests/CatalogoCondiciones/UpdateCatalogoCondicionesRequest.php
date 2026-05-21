<?php

namespace App\Http\Requests\CatalogoCondiciones;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCatalogoCondicionesRequest',
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Alergia a la penicilina'),
        new OA\Property(property: 'codigo', type: 'string', example: 'Alergia a la penicilina'),
    ]
)]
class UpdateCatalogoCondicionesRequest extends FormRequest
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
