<?php

namespace App\Http\Requests\Adopcion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EstadisticaFechasRequest",
    required: ["fecha_inicio", "fecha_fin"],
    properties: [
        new OA\Property(property: "fecha_inicio", type: "string", format: "date", description: "Fecha de inicio para el rango de estadísticas (formato YYYY-MM-DD)"),
        new OA\Property(property: "fecha_fin", type: "string", format: "date", description: "Fecha de fin para el rango de estadísticas (formato YYYY-MM-DD)"),
    ],
    type: "object"
)]
class EstadisticaFechasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'El campo fecha_inicio es obligatorio.',
            'fecha_inicio.date' => 'El campo fecha_inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'El campo fecha_fin es obligatorio.',
            'fecha_fin.date' => 'El campo fecha_fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'El campo fecha_fin debe ser una fecha posterior o igual a fecha_inicio.',
        ];
    }
}
