<?php

namespace App\Http\Requests\Adopcion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EstadisticaCampaniaRequest",
    required: ["campania_id"],
    properties: [
        new OA\Property(property: "campania_id", type: "string", format: "uuid", description: "ID de la campaña para la cual se desean obtener las estadísticas"),
    ],
    type: "object"
)]
class EstadisticaCampaniaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campania_id' => ['required', 'uuid', 'exists:campanias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'campania_id.required' => 'El campo campania_id es obligatorio.',
            'campania_id.uuid' => 'El campo campania_id debe ser un UUID válido.',
            'campania_id.exists' => 'La campaña especificada no existe.',
        ];
    }
}
