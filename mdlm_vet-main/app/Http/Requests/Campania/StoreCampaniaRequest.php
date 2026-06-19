<?php

namespace App\Http\Requests\Campania;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\EstadoCampania;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCampaniaRequest',
    description: 'Request para crear una nueva campaña de salud pública',
    required: ['nombre', 'fecha_hora_inicio', 'fecha_hora_fin', 'responsable_id'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Campaña Antirrábica 2026'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Vacunación masiva gratuita en el parque central.'),
        new OA\Property(property: 'lugar', type: 'string', example: 'Plaza de Armas SJM'),
        new OA\Property(property: 'fecha_hora_inicio', type: 'string', format: 'date-time', example: '2026-05-01 09:00:00'),
        new OA\Property(property: 'fecha_hora_fin', type: 'string', format: 'date-time', example: '2026-05-03 17:00:00'),
        new OA\Property(property: 'estado', type: 'string', example: 'planificada', description: 'El estado de la campaña. Por defecto es planificada.'),
        new OA\Property(property: 'responsable_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', description: 'ID del personal a cargo'),
    ]
)]
class StoreCampaniaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'fecha_hora_inicio' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'fecha_hora_fin' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after_or_equal:fecha_hora_inicio'],
            'estado' => ['nullable', Rule::enum(EstadoCampania::class)],
            'responsable_id' => ['required', 'string', 'uuid', 'exists:personal,id'], 
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la campaña es obligatorio.',
            'fecha_hora_inicio.required' => 'La fecha y hora de inicio son obligatorias.',
            'fecha_hora_inicio.date_format' => 'La fecha y hora de inicio deben tener el formato YYYY-MM-DD HH:MM:SS.',
            'fecha_hora_fin.required' => 'La fecha y hora de finalización son obligatorias.',
            'fecha_hora_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            'estado.in' => 'El estado de la campaña debe ser uno de los valores permitidos.',
            'responsable_id.required' => 'Debe asignar a un responsable de la campaña.',
            'responsable_id.exists' => 'El responsable seleccionado no existe en los registros del personal.',
        ];
    }
}