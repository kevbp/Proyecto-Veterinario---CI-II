<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Historial',
    title: 'Historial',
    description: 'Historial médico del animal',
    required: [],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_hora', type: 'string', format: 'date-time'),
        new OA\Property(property: 'eventable_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'eventable_type', type: 'string', example: 'App\\Models\\Consulta'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Historial extends Model
{
    use HasUuids;

    protected $fillable = [
        'animal_id',
        'fecha_hora',
        'eventable_id',     // Para el polimorfismo
        'eventable_type',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'animal_id' => 'string',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function eventable()
    {
        return $this->morphTo();
    }
}
