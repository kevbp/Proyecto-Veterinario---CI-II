<?php

namespace App\Http\Requests\CatalogoAlergias;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCatalogoAlergiasRequest',
    required: ['nombre', 'categoria', 'codigo'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Pollo'),
        new OA\Property(property: 'categoria', type: 'string', example: 'Alimenticia|Ambiental|Medicamento|Biologico|Otro'),
        new OA\Property(property: 'codigo', type: 'string', example: 'POLLO'),
    ]
)]
class StoreCatalogoAlergiasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'categoria.required' => 'La categoria es requerida',
            'codigo.required' => 'El codigo es requerido',
        ];
    }
}
