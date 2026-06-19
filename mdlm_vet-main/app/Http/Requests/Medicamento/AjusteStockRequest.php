<?php

namespace App\Http\Requests\Medicamento;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AjusteStockRequest',
    title: 'AjusteStockRequest',
    description: 'Request para restar stock de un medicamento',
    required: ['stock'],
    properties: [
        new OA\Property(property: 'stock', type: 'number', example: 10),
    ]
)]

class AjusteStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'stock' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'stock.min' => 'El stock no puede ser negativo.'
        ];
    }
}