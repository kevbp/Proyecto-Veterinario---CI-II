<?php

namespace App\Models;

use App\Enums\EstadoCampania;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Campania',
    description: 'Modelo de campaña de vacunación o desparasitación',
    required: ['nombre', 'lugar', 'fecha_hora_inicio', 'fecha_hora_fin', 'estado', 'responsable_id'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Campaña de Vacunación Primavera 2024'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Vacunación contra rabia y parvovirus para perros y gatos en la primavera de 2024.', nullable: true),
        new OA\Property(property: 'lugar', type: 'string', example: 'Centro Comunitario de Salud Animal'),
        new OA\Property(property: 'fecha_hora_inicio', type: 'string', format: 'date-time', example: '2024-09-01T08:00:00Z'),
        new OA\Property(property: 'fecha_hora_fin', type: 'string', format: 'date-time', example: '2024-09-30T17:00:00Z'),
        new OA\Property(property: 'estado', type: 'string', enum: ['programada', 'en_progreso', 'finalizada'], example: 'programada'),
        new OA\Property(property: 'responsable_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Campania extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'campanias';

    protected $fillable = [
        'nombre',           
        'descripcion',      
        'lugar',            
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estado',
        'responsable_id',   // Jefe de Campaña (Personal)
    ];

    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
        'estado' => EstadoCampania::class,
    ];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'responsable_id');
    }

    public function vacunas(): HasMany
    {
        return $this->hasMany(VacunaAnimal::class, 'campania_id');
    }

    public function desparasitaciones(): HasMany
    {
        return $this->hasMany(Desparasitacion::class, 'campania_id');
    }
}
