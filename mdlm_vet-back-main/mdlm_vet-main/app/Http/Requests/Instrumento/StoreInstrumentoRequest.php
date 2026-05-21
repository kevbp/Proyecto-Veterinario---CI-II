<?php

namespace App\Http\Requests\Instrumento;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreInstrumentoRequest',
    title: 'StoreInstrumentoRequest',
    description: 'Request para crear un instrumento',
    required: ['codigo', 'nombre'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'EST-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Estetoscopio'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Estetoscopio veterinario', nullable: true),
        new OA\Property(property: 'stock', type: 'integer', example: 10),
        new OA\Property(property: 'estado', type: 'string', example: 'activo'),
        new OA\Property(property: 'foto', type: 'string', example: 'instrumentos/estetoscopio.jpg', nullable: true),
    ]
)]
class StoreInstrumentoRequest extends FormRequest
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
            'codigo' => ['required', 'string', 'max:50', 'unique:instrumentos,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'estado' => ['sometimes', 'string', 'in:activo,inactivo,mantenimiento'],
            'foto' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'nombre.required' => 'El nombre es obligatorio.',
            'stock.min' => 'El stock no puede ser negativo.',
            'estado.in' => 'El estado debe ser: activo, inactivo o mantenimiento.',
        ];
    }
}
