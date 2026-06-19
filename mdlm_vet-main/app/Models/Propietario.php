<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Propietario',
    title: 'Propietario',
    description: 'Modelo de propietario de mascota',
    required: ['tipo_doc_id', 'nro_doc', 'nombre', 'paterno', 'email'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        new OA\Property(property: 'tipo_doc_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: '12345678'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez', nullable: true),
        new OA\Property(property: 'email', type: 'string', example: 'juan.perez@example.com'),
        new OA\Property(property: 'celular', type: 'string', example: '999888777', nullable: true),
        new OA\Property(property: 'nro_emergencia', type: 'string', example: '999888666', nullable: true),
        new OA\Property(property: 'vivienda_direccion', type: 'string', nullable: true, example: 'Av. La Molina 123, Lima, Perú'),
        new OA\Property(property: 'vivienda_latitud', type: 'number', format: 'double', nullable: true, example: -12.0773588),
        new OA\Property(property: 'vivienda_longitud', type: 'number', format: 'double', nullable: true, example: -76.9438497),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Propietario extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_doc_id',
        'nro_doc',
        'nombre',
        'paterno',
        'materno',
        'email',
        'celular',
        'nro_emergencia',
        'vivienda_direccion',
        'vivienda_latitud',
        'vivienda_longitud',
    ];

    protected $casts = [
        'user_id' => 'string',
        'tipo_doc_id' => 'string',
        'nro_doc' => 'integer',
        'nombre' => 'string',
        'paterno' => 'string',
        'materno' => 'string',
        'email' => 'string',
        'celular' => 'string',
        'nro_emergencia' => 'string',
        'vivienda_direccion' => 'string',
        'vivienda_latitud' => 'float',
        'vivienda_longitud' => 'float',
    ];

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_doc_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animales(): HasMany
    {
        return $this->hasMany(Animal::class, 'propietario_id');
    }

    /**
     * Indica si el propietario ya fue vinculado a un usuario del SSO.
     */
    public function estaVinculado(): bool
    {
        return $this->user_id !== null;
    }
}
