<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Resultado',
    description: 'Modelo que representa los resultados de un examen médico',
    required: ['examen_id', 'hallazgos', 'valores'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'examen_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'hallazgos', type: 'string', example: 'Hallazgos del examen'),
        new OA\Property(property: 'valores', type: 'string', example: 'Valores obtenidos'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones adicionales'),
        new OA\Property(property: 'interpretacion', type: 'string', example: 'Interpretación de los resultados'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Resultado extends Model
{
    use HasUuids;

    protected $fillable = [
        'examen_id',
        'hallazgos',
        'valores',
        'observaciones',
        'interpretacion',
    ];

    protected $casts = [
        'examen_id' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }
}
