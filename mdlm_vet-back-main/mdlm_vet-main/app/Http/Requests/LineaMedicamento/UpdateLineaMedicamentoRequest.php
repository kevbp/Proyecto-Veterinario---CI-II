<?php

namespace App\Http\Requests\LineaMedicamento;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateLineaMedicamentoRequest',
    title: 'UpdateLineaMedicamentoRequest',
    description: 'Request para actualizar una línea de medicamento',
    properties: [
        new OA\Property(property: 'receta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', example: 'MED-001'),
        new OA\Property(property: 'cantidad', type: 'numeric', format: 'float', example: 2.00),
        new OA\Property(property: 'dosis', type: 'string', example: '1 comprimido'),
        new OA\Property(property: 'frecuencia', type: 'string', example: 'cada 12 horas'),
        new OA\Property(property: 'duracion', type: 'string', example: '5 días'),
        new OA\Property(property: 'instruccion_especifica', type: 'string', example: 'Tomar con alimentos'),
    ]
)]
class UpdateLineaMedicamentoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receta_id' => ['sometimes', 'string', 'uuid', 'exists:recetas,id'],
            'medicamento_id' => ['sometimes', 'string', 'exists:medicamentos,codigo'],
            'cantidad' => ['sometimes', 'numeric', 'min:0'],
            'dosis' => ['sometimes', 'string', 'max:255'],
            'frecuencia' => ['sometimes', 'string', 'max:255'],
            'duracion' => ['sometimes', 'string', 'max:255'],
            'instruccion_especifica' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'receta_id.string' => 'El campo receta_id debe ser una cadena de texto.',
            'receta_id.uuid' => 'El campo receta_id debe ser un UUID válido.',
            'receta_id.exists' => 'El receta_id proporcionado no existe en la base de datos.',
            'medicamento_id.string' => 'El campo medicamento_id debe ser una cadena de texto.',
            'medicamento_id.exists' => 'El medicamento_id proporcionado no existe en la base de datos.',
            'cantidad.numeric' => 'El campo cantidad debe ser un número.',
            'cantidad.min' => 'El campo cantidad debe ser al menos 0.',
            'dosis.string' => 'El campo dosis debe ser una cadena de texto.',
            'dosis.max' => 'El campo dosis no puede exceder los 255 caracteres.',
            'frecuencia.string' => 'El campo frecuencia debe ser una cadena de texto.',
            'frecuencia.max' => 'El campo frecuencia no puede exceder los 255 caracteres.',
            'duracion.string' => 'El campo duracion debe ser una cadena de texto.',
            'duracion.max' => 'El campo duracion no puede exceder los 255 caracteres.',
            'instruccion_especifica.string' => 'El campo instruccion_especifica debe ser una cadena de texto.',
            'instruccion_especifica.max' => 'El campo instruccion_especifica no puede exceder los 255 caracteres.',
        ];
    }
}
