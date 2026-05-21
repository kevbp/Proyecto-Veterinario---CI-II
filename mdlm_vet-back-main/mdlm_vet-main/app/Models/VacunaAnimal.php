<?php

namespace App\Models;

use App\Traits\RegistroHistorial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VacunaAnimal',
    description: 'VacunaAnimal',
    required: ['animal_id', 'esquema_vacuna_id', 'fecha_aplicacion', 'fecha_proxima', 'dosis', 'lote', 'fabricante', 'observaciones', 'personal_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'animal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cantidad', type: 'number', format: 'float'),
        new OA\Property(property: 'esquema_vacuna_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_aplicacion', type: 'string', format: 'date-time'),
        new OA\Property(property: 'fecha_proxima', type: 'string', format: 'date-time'),
        new OA\Property(property: 'dosis', type: 'string', example: '5 ml'),
        new OA\Property(property: 'lote', type: 'string', example: '123456'),
        new OA\Property(property: 'fabricante', type: 'string', example: 'Fabricante'),
        new OA\Property(property: 'observaciones', type: 'string', example: 'Observaciones'),
        new OA\Property(property: 'personal_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'campania_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
    ]
)]
class VacunaAnimal extends Model
{
    use HasUuids, RegistroHistorial;

    protected $fillable = [
        'fecha_aplicacion',
        'fecha_proxima',
        'dosis',
        'lote',
        'fabricante',
        'observaciones',
        'animal_id',
        'cantidad',
        'esquema_vacuna_id',
        'medicamento_id',
        'personal_id',
        'consulta_id',
        'campania_id'
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'fecha_proxima' => 'date',
        'animal_id' => 'string',
        'cantidad' => 'float',
        'esquema_vacuna_id' => 'string',
        'medicamento_id' => 'string',
        'personal_id' => 'string',
        'consulta_id' => 'string',
        'campania_id' => 'string'
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function esquemaVacuna(): BelongsTo
    {
        return $this->belongsTo(EsquemaVacuna::class);
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class);
    }

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function campania(): BelongsTo
    {
        return $this->belongsTo(Campania::class);
    }
}
