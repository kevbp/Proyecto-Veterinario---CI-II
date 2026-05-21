<?php

namespace App\Http\Requests\Examen;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateExamenRequest',
    properties: [
        new OA\Property(property: 'descripcion', type: 'string', example: 'Examen completo de sangre'),
        new OA\Property(property: 'estado', type: 'string', example: 'Pendiente'),
        new OA\Property(property: 'fecha_resultado', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
    ],
    type: 'object'
)]
class UpdateExamenRequest extends FormRequest
{
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
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string',
            'fecha_resultado' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'fecha_resultado.date_format' => 'La fecha de resultado debe tener el formato Y-m-d H:i:s.',
        ];
    }
}
