<?php

namespace App\Http\Requests\Receta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreRecetaRequest',
    description: 'Request para crear una receta veterinaria',
    required: ['consulta_id', 'estado_receta', 'indicaciones_generales', 'fecha_emision', 'fecha_vencimiento'],
    properties: [
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'estado_receta', type: 'string', example: 'finalizada'),
        new OA\Property(property: 'indicaciones_generales', type: 'string', example: 'El paciente debe estar relajado durante la administración del medicamento.'),
        new OA\Property(property: 'fecha_emision', type: 'string', format: 'date-time', example: '2024-01-01 10:00:00'),
        new OA\Property(property: 'fecha_vencimiento', type: 'string', format: 'date-time', example: '2024-01-01 10:00:00'),

        new OA\Property(
            property: 'lineas_medicamento',
            type: 'array',
            description: 'Lista de medicamentos recetados',
            items: new OA\Items(
                required: ['medicamento_id', 'cantidad'],
                properties: [
                    new OA\Property(property: 'medicamento_id', type: 'string', example: 'MED-001'),
                    new OA\Property(property: 'cantidad', type: 'number', example: 2),
                    new OA\Property(property: 'dosis', type: 'string', example: '1 pastilla'),
                    new OA\Property(property: 'frecuencia', type: 'string', example: 'Cada 8 horas'),
                    new OA\Property(property: 'duracion', type: 'string', example: 'Por 5 días'),
                    new OA\Property(property: 'instruccion_especifica', type: 'string', example: 'Tomar junto con la comida'),
                ]
            )
        )
    ]
)]
class StoreRecetaRequest extends FormRequest
{
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
            'consulta_id' => ['required', 'string', 'uuid', 'exists:consultas,id'],
            'estado_receta' => ['required', 'string'],
            'indicaciones_generales' => ['required', 'string'],
            'fecha_emision' => ['required', 'string', 'date_format:Y-m-d H:i:s'],
            'fecha_vencimiento' => ['required', 'string', 'date_format:Y-m-d H:i:s'],
            'lineas_medicamento' => ['nullable', 'array'],

            'lineas_medicamento.*.medicamento_id' => ['required_with:lineas_medicamento', 'string', 'exists:medicamentos,codigo'],
            'lineas_medicamento.*.cantidad' => ['required_with:lineas_medicamento', 'integer', 'min:1'],
            'lineas_medicamento.*.dosis' => ['required_with:lineas_medicamento', 'string', 'max:255'],
            'lineas_medicamento.*.frecuencia' => ['required_with:lineas_medicamento', 'string', 'max:255'],
            'lineas_medicamento.*.duracion' => ['required_with:lineas_medicamento', 'string', 'max:255'],
            'lineas_medicamento.*.instruccion_especifica' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'consulta_id.required' => 'El ID de la consulta es requerido',
            'consulta_id.string' => 'El ID de la consulta debe ser una cadena de texto',
            'consulta_id.uuid' => 'El ID de la consulta debe ser un UUID válido',
            'consulta_id.exists' => 'El ID de la consulta no existe en la base de datos',
            'estado_receta.required' => 'El estado de la receta es requerido',
            'estado_receta.string' => 'El estado de la receta debe ser una cadena de texto',
            'indicaciones_generales.required' => 'Las indicaciones generales son requeridas',
            'indicaciones_generales.string' => 'Las indicaciones generales deben ser una cadena de texto',
            'fecha_emision.required' => 'La fecha de emisión es requerida',
            'fecha_emision.string' => 'La fecha de emisión debe ser una cadena de texto',
            'fecha_emision.date_format' => 'La fecha de emisión debe tener el formato Y-m-d H:i:s',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es requerida',
            'fecha_vencimiento.string' => 'La fecha de vencimiento debe ser una cadena de texto',
            'fecha_vencimiento.date_format' => 'La fecha de vencimiento debe tener el formato Y-m-d H:i:s',

            'lineas_medicamento.array' => 'Las líneas de medicamento deben ser un arreglo',
            'lineas_medicamento.*.medicamento_id.required_with' => 'El ID del medicamento es requerido para cada línea de medicamento',
            'lineas_medicamento.*.medicamento_id.string' => 'El ID del medicamento debe ser una cadena de texto',
            'lineas_medicamento.*.medicamento_id.exists' => 'El ID del medicamento no existe en la base de datos',
            'lineas_medicamento.*.cantidad.required_with' => 'La cantidad es requerida para cada línea de medicamento',
            'lineas_medicamento.*.cantidad.integer' => 'La cantidad debe ser un número entero',
            'lineas_medicamento.*.cantidad.min' => 'La cantidad debe ser al menos 1',
            'lineas_medicamento.*.dosis.required_with' => 'La dosis es requerida para cada línea de medicamento',
            'lineas_medicamento.*.dosis.string' => 'La dosis debe ser una cadena de texto',
            'lineas_medicamento.*.dosis.max' => 'La dosis no puede exceder los 255 caracteres',
            'lineas_medicamento.*.frecuencia.required_with' => 'La frecuencia es requerida para cada línea de medicamento',
            'lineas_medicamento.*.frecuencia.string' => 'La frecuencia debe ser una cadena de texto',
            'lineas_medicamento.*.frecuencia.max' => 'La frecuencia no puede exceder los 255 caracteres',
            'lineas_medicamento.*.duracion.required_with' => 'La duración es requerida para cada línea de medicamento',
            'lineas_medicamento.*.duracion.string' => 'La duración debe ser una cadena de texto',
            'lineas_medicamento.*.duracion.max' => 'La duración no puede exceder los 255 caracteres',
            'lineas_medicamento.*.instruccion_especifica.string' => 'Las instrucciones específicas deben ser una cadena de texto',
            'lineas_medicamento.*.instruccion_especifica.max' => 'Las instrucciones específicas no pueden exceder los 255 caracteres',
        ];
    }
}
