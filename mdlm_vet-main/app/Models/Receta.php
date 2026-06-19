<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Receta',
    title: 'Receta',
    description: 'Modelo de receta veterinaria',
    required: ['codigo', 'indicaciones_generales', 'fecha_emision', 'fecha_vencimiento'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'estado_receta', type: 'string', example: 'finalizada'),
        new OA\Property(property: 'indicaciones_generales', type: 'string', example: 'El paciente debe estar relajado durante la administración del medicamento.'),
        new OA\Property(property: 'fecha_emision', type: 'string', format: 'date-time'),
        new OA\Property(property: 'fecha_vencimiento', type: 'string', format: 'date-time'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
    type: 'object'
)]
class Receta extends Model
{
    use HasUuids;

    protected $table = 'recetas';

    protected $fillable = [
        'consulta_id',
        'estado_receta',
        'indicaciones_generales',
        'fecha_emision',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'consulta_id' => 'string',
        'estado_receta' => 'string',
        'indicaciones_generales' => 'string',
        'fecha_emision' => 'datetime',
        'fecha_vencimiento' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class);
    }

    public function lineasMedicamentos(): HasMany
    {
        return $this->hasMany(LineaMedicamento::class);
    }
}
