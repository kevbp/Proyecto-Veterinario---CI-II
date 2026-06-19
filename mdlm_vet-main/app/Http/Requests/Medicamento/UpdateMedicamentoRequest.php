<?php

namespace App\Http\Requests\Medicamento;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateMedicamentoRequest',
    title: 'UpdateMedicamentoRequest',
    description: 'Request para actualizar un medicamento',
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'MED-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Amoxicilina'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Antibiótico de amplio espectro', nullable: true),
        new OA\Property(property: 'stock', type: 'number', format: 'float', example: 100.00),
        new OA\Property(property: 'estado', type: 'string', example: 'activo'),
        new OA\Property(property: 'foto', type: 'string', example: 'medicamentos/amoxicilina.jpg', nullable: true),
    ]
)]
class UpdateMedicamentoRequest extends FormRequest
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
            'codigo' => ['sometimes', 'string', 'max:50', 'unique:medicamentos,codigo,' . $this->route('medicamento')?->id],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'stock' => ['sometimes', 'numeric', 'min:0'],
            'estado' => ['sometimes', 'string', 'in:activo,inactivo,agotado'],
            'foto' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'codigo.unique' => 'Este código ya está registrado.',
            'stock.min' => 'El stock no puede ser negativo.',
            'estado.in' => 'El estado debe ser: activo, inactivo o agotado.',
        ];
    }
}
