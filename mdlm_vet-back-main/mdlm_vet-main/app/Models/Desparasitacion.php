<?php

namespace App\Models;

use App\Traits\RegistroHistorial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Desparasitacion',
    description: 'Desparasitacion',
    required: ['animal_id', 'medicamento_id', 'fecha_aplicacion', 'fecha_aplicacion_sgte', 'dosis', 'via', 'observaciones', 'personal_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_aplicacion', type: 'string', format: 'date-time'),
        new OA\Property(property: 'fecha_aplicacion_sgte', type: 'string', format: 'date-time'),
        new OA\Property(property: 'dosis', type: 'string', example: '10mg'),
        new OA\Property(property: 'via', type: 'string', example: 'Oral'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
        new OA\Property(property: 'cantidad', type: 'number', format: 'float'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
    ],
    type: 'object'
)]
class Desparasitacion extends Model
{
    use HasUuids, RegistroHistorial;

    protected $table = 'desparasitaciones';

    protected $fillable = [
        'animal_id',
        'medicamento_id',
        'fecha_aplicacion',
        'fecha_aplicacion_sgte',
        'dosis',
        'via',
        'observaciones',
        'cantidad',
        'campania_id',
        'personal_id',
        'consulta_id'
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'fecha_aplicacion_sgte' => 'date',
        'cantidad' => 'float',
        'animal_id' => 'string',
        'medicamento_id' => 'string',
        'personal_id' => 'string',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }

    public function campaña(): BelongsTo
    {
        return $this->belongsTo(Campania::class, 'campania_id');
    }
}
