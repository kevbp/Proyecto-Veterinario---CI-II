<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EstadoCita",
    title: "Estado de la cita",
    description: "Representa el estado de una cita médica",
    required: ['codigo','nombre','color_hex'],
    properties: [
        new OA\Property(property: "id",type: "string",format: "uuid",example: "123e4567-e89b-12d3-a456-426614174000"),
        new OA\Property(property: "codigo",type: "string",example: "PEN"),
        new OA\Property(property: "nombre",type: "string",example: "Pendiente"),
        new OA\Property(property: "color_hex",type: "string",example: "#FF0000"),
        new OA\Property(property: "created_at",type: "string",format: "date-time"),
        new OA\Property(property: "updated_at",type: "string",format: "date-time")
    ]
)]

class EstadoCita extends Model
{
    use HasUuids;

    protected $fillable = [
        'codigo',
        'nombre',
        'color_hex'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'color_hex' => 'string',
    ];
}
