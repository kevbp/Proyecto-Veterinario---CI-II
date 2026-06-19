<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EsquemaVacuna",
    title: "EsquemaVacuna",
    description: "EsquemaVacuna",
    required: ['codigo','nombre'],
    properties: [
        new OA\Property(property: "id",type: "string",format: "uuid",example: "123e4567-e89b-12d3-a456-426614174000"),
        new OA\Property(property: "codigo",type: "string",example: "ESQ-001"),
        new OA\Property(property: "nombre",type: "string",example: "Esquema de Vacunación Canino"),
        new OA\Property(property: "enfermedad",type: "string",example: "Rabia"),
        new OA\Property(property: "dosis",type: "string",example: "1 ml"),
        new OA\Property(property: "intervalo_dias",type: "integer",example: 30),
        new OA\Property(property: "descripcion",type: "string",example: "Esquema de vacunación para perros"),
        new OA\Property(property: "especie_id",type: "string",format: "uuid",example: "123e4567-e89b-12d3-a456-426614174000"),
        new OA\Property(property: "created_at",type: "string",format: "date-time"),
        new OA\Property(property: "updated_at",type: "string",format: "date-time")
    ]
)]
class EsquemaVacuna extends Model
{
    use HasUuids;

    protected $fillable = [
        'codigo',
        'nombre',
        'enfermedad',
        'dosis',
        'intervalo_dias',
        'descripcion',
        'especie_id'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'enfermedad' => 'string',
        'dosis' => 'string',
        'intervalo_dias' => 'integer',
        'especie_id' => 'string',
    ];

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }
}
