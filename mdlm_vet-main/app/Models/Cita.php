<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Cita',
    title: 'Cita',
    description: 'Modelo de cita',
    required: ['fecha_hora', 'motivo', 'estado_cita_id', 'animal_id', 'personal_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2022-01-01T10:00:00.000000Z'),
        new OA\Property(property: 'motivo', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'estado_cita_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Cita extends Model
{
    use HasUuids;

    protected $fillable = [
        'fecha_hora',
        'motivo',
        'estado_cita_id',
        'observaciones',
        'animal_id',
        'personal_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'motivo' => 'string',
        'estado_cita_id' => 'string',
        'observaciones' => 'string',
        'animal_id' => 'string',
        'personal_id' => 'string',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function estadoCita(): BelongsTo
    {
        return $this->belongsTo(EstadoCita::class);
    }
}
