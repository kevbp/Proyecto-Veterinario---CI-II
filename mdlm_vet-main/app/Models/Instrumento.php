<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Instrumento',
    title: 'Instrumento',
    description: 'Modelo de instrumento veterinario',
    required: ['codigo', 'nombre', 'stock'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'EST-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Estetoscopio'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Estetoscopio veterinario de doble campana', nullable: true),
        new OA\Property(property: 'stock', type: 'integer', example: 10),
        new OA\Property(property: 'estado', type: 'string', example: 'activo', nullable: true),
        new OA\Property(property: 'foto', type: 'string', example: 'instrumentos/estetoscopio.jpg', nullable: true),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Instrumento extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'stock',
        'estado',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'stock' => 'integer',
        'estado' => 'string',
        'foto' => 'string',
        'user_id' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
