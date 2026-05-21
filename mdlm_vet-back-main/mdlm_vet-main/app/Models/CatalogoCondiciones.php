<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CatalogoCondiciones',
    title: 'CatalogoCondiciones',
    description: 'Modelo de catalogo de condiciones',
    required: ['nombre', 'codigo'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Alergia a la penicilina'),
        new OA\Property(property: 'codigo', type: 'string', example: 'Alergia a la penicilina'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class CatalogoCondiciones extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
        'codigo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'nombre' => 'string',
        'codigo' => 'string',
    ];
}
