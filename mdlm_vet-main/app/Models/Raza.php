<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Raza',
    title: 'Raza',
    description: 'Modelo de raza',
    required: ['nombre', 'codigo', 'peligroso', 'especie_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Labrador'),
        new OA\Property(property: 'codigo', type: 'string', example: 'CAN001'),
        new OA\Property(property: 'peligroso', type: 'boolean', example: false),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Raza extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'peligroso',
        'especie_id',
    ];

    protected $casts = [
        'peligroso' => 'boolean',
        'especie_id' => 'string',
    ];

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }
}
