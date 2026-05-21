<?php

namespace App\Models;

use App\Traits\RegistroHistorial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Consulta',
    required: ['fecha_hora', 'motivo', 'diagnostico', 'tratamiento', 'peso_registrado', 'observaciones', 'animal_id', 'personal_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time', example: '2022-01-01T10:00:00.000000Z'),
        new OA\Property(property: 'motivo', type: 'string', example: 'Consulta general'),
        new OA\Property(property: 'diagnostico', type: 'string', example: 'Diagnostico'),
        new OA\Property(property: 'tratamiento', type: 'string', example: 'Tratamiento'),
        new OA\Property(property: 'peso_registrado', type: 'number', example: 10.5),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cita_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2022-01-01T10:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2022-01-01T10:00:00.000000Z'),
    ]
)]
class Consulta extends Model
{
    use HasUuids, RegistroHistorial;

    protected $fillable = [
        'fecha_hora',
        'motivo',
        'diagnostico',
        'tratamiento',
        'peso_registrado',
        'observaciones',
        'animal_id',
        'personal_id',
        'cita_id',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }
}
