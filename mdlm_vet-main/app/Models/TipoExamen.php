<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TipoExamen',
    title: 'Tipo de Examen',
    description: 'Representa el tipo de examen médico',
    required: ['codigo', 'nombre', 'categoria', 'precio_ref'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'EXA-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Examen de Sangre'),
        new OA\Property(property: 'categoria', type: 'string', example: 'Laboratorio'),
        new OA\Property(property: 'precio_ref', type: 'number', format: 'float', example: 100.00),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class TipoExamen extends Model
{
    use HasUuids;

    protected $fillable = [
        'codigo',
        'nombre',
        'categoria',
        'precio_ref',
    ];

    protected $casts = [
        'precio_ref' => 'decimal:2',
    ];
}
