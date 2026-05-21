<?php

namespace App\Http\Requests\EsquemaVacuna;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateEsquemaVacunaRequest',
    title: 'UpdateEsquemaVacunaRequest',
    description: 'Request para actualizar un esquema de vacuna',
    required: ['codigo', 'nombre', 'enfermedad', 'dosis', 'intervalo_dias', 'especie_id'],
    properties: [
        new OA\Property(property: 'codigo', type: 'string', example: 'ESQ-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Vacuna Antirrábica Canina'),
        new OA\Property(property: 'enfermedad', type: 'string', example: 'Rabia'),
        new OA\Property(property: 'dosis', type: 'string', example: '1 ml'),
        new OA\Property(property: 'intervalo_dias', type: 'integer', example: 365),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Vacuna obligatoria contra la rabia'),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
    ]
)]
class UpdateEsquemaVacunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:20|unique:esquema_vacunas,codigo,' . $this->route('esquema_vacuna'),
            'nombre' => 'required|string|max:255',
            'enfermedad' => 'required|string|max:255',
            'dosis' => 'required|string|max:100',
            'intervalo_dias' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:1000',
            'especie_id' => 'required|uuid|exists:especies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'El código ya existe.',
            'codigo.max' => 'El código no puede exceder los 20 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'enfermedad.required' => 'La enfermedad es obligatoria.',
            'dosis.required' => 'La dosis es obligatoria.',
            'intervalo_dias.required' => 'El intervalo en días es obligatorio.',
            'intervalo_dias.integer' => 'El intervalo debe ser un número entero.',
            'intervalo_dias.min' => 'El intervalo debe ser al menos 1 día.',
            'descripcion.max' => 'La descripción no puede exceder los 1000 caracteres.',
            'especie_id.required' => 'La especie es obligatoria.',
            'especie_id.exists' => 'La especie seleccionada no existe.',
        ];
    }
}
