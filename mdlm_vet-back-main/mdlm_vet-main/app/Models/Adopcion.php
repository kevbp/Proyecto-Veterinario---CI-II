<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Adopcion',
    description: 'Modelo de adopción de un animal',
    required: ['animal_id', 'propietario_anterior_id', 'propietario_nuevo_id', 'fecha_adopcion'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'propietario_anterior_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'propietario_nuevo_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_adopcion', type: 'string', format: 'date-time'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'El animal fue adoptado en buen estado de salud.'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Adopcion extends Model
{
    use HasUuids;

    protected $fillable = [
        'animal_id',
        'propietario_anterior_id',
        'propietario_nuevo_id',
        'campania_id',
        'fecha_adopcion',
        'observaciones'
    ];

    protected $casts = [
        'fecha_adopcion' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function propietarioAnterior()
    {
        return $this->belongsTo(Propietario::class, 'propietario_anterior_id');
    }

    public function propietarioNuevo()
    {
        return $this->belongsTo(Propietario::class, 'propietario_nuevo_id');
    }

}
