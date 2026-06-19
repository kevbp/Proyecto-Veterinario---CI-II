<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Animal_Condicion',
    title: 'Animal_Condicion',
    description: 'Modelo de animal_condicion',
    required: ['animal_id', 'condicion_id', 'observaciones', 'fecha_diagnostico', 'estado_clinico', 'consulta_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'condicion_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
        new OA\Property(property: 'fecha_diagnostico', type: 'string', format: 'date', example: '2022-01-01'),
        new OA\Property(property: 'estado_clinico', type: 'string', example: 'estable'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Animal_Condicion extends Model
{
    use HasUuids;

    protected $table = 'animal_condicion';

    protected $fillable = [
        'animal_id',
        'condicion_id',
        'observaciones',
        'fecha_diagnostico',
        'estado_clinico',
        'consulta_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'animal_id' => 'string',
        'condicion_id' => 'string',
        'observaciones' => 'string',
        'fecha_diagnostico' => 'date',
        'estado_clinico' => 'string',
        'consulta_id' => 'string',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function condicion(): BelongsTo
    {
        return $this->belongsTo(CatalogoCondiciones::class, 'condicion_id');
    }

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }
}
