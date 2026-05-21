<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LineaMedicamento',
    description: 'Modelo de relación entre receta y medicamento',
    required: ['receta_id', 'medicamento_id', 'dosis', 'frecuencia', 'duracion'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'receta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'medicamento_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'cantidad', type: 'integer', example: 2),
        new OA\Property(property: 'dosis', type: 'string', example: '1 comprimido'),
        new OA\Property(property: 'frecuencia', type: 'string', example: 'cada 12 horas'),
        new OA\Property(property: 'duracion', type: 'string', example: '5 días'),
        new OA\Property(property: 'instruccion_especifica', type: 'string', example: 'Tomar con alimentos'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class LineaMedicamento extends Model
{
    use HasUuids;

    protected $fillable = [
        'receta_id',
        'medicamento_id',
        'cantidad',
        'dosis',
        'frecuencia',
        'duracion',
        'instruccion_especifica'
    ];

    protected $casts = [
        'receta_id' => 'string',
        'medicamento_id' => 'string',
        'dosis' => 'string',
        'frecuencia' => 'string',
        'duracion' => 'string',
        'cantidad' => 'integer',
        'instruccion_especifica' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }
}
