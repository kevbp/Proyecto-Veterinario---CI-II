<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Medicamento',
    description: 'Modelo de medicamento veterinario',
    required: ['codigo', 'nombre', 'stock'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'codigo', type: 'string', example: 'MED-001'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Amoxicilina'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Antibiótico de amplio espectro', nullable: true),
        new OA\Property(property: 'stock', type: 'integer', example: 100),
        new OA\Property(property: 'estado', type: 'string', example: 'activo', nullable: true),
        new OA\Property(property: 'foto', type: 'string', example: 'medicamentos/amoxicilina.jpg', nullable: true),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Medicamento extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'stock',
        'estado',
        'foto',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'stock' => 'float',
        'estado' => 'string',
        'foto' => 'string',
        'user_id' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lineaMedicamentos(): HasMany
    {
        return $this->hasMany(LineaMedicamento::class);
    }
}
