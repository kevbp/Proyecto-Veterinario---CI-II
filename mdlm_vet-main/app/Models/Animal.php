<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OpenApi\Attributes as OA;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[OA\Schema(
    schema: 'Animal',
    title: 'Animal',
    description: 'Modelo de animal/mascota',
    required: ['propietario_id', 'nombre', 'especie_id', 'raza', 'sexo', 'peso', 'color', 'esterilizacion'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'propietario_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Firulais'),
        new OA\Property(property: 'especie_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'raza_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'sexo', type: 'string', enum: ['Macho', 'Hembra'], example: 'Macho'),
        new OA\Property(property: 'color', type: 'string', example: 'Dorado'),
        new OA\Property(property: 'esterilizacion', type: 'boolean', example: true),
        new OA\Property(property: 'fallecido', type: 'boolean', example: false),
        new OA\Property(property: 'fecha_fallecimiento', type: 'string', format: 'date', nullable: true, example: '2026-05-07'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Animal extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    protected $fillable = [
        'propietario_id',
        'nombre',
        'especie_id',
        'raza_id',
        'sexo',
        'color',
        'esterilizacion',
        'fallecido',
        'fecha_fallecimiento',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'propietario_id' => 'string',
        'nombre' => 'string',
        'especie_id' => 'string',
        'raza_id' => 'string',
        'sexo' => 'string',
        'color' => 'string',
        'esterilizacion' => 'boolean',
        'fallecido' => 'boolean',
        'fecha_fallecimiento' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Solo guarda los cambios en los campos especificados
            ->logOnly(['esterilizacion', 'propietario_id', 'fallecido', 'fecha_fallecimiento'])

            // ¡CRÍTICO PARA EL RENDIMIENTO! Solo guarda si los datos realmente cambiaron
            ->logOnlyDirty()

            // No guardes un registro vacío si el usuario le dio "Guardar" sin cambiar nada
            ->dontLogEmptyChanges()

            // (Opcional) Ponle un nombre legible al log
            ->useLogName('Mascotas');
    }

    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Propietario::class);
    }

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }

    public function raza(): BelongsTo
    {
        return $this->belongsTo(Raza::class, 'raza_id');
    }

    public function alergias()
    {
        return $this->hasMany(Animal_Alergia::class);
    }

    public function condiciones()
    {
        return $this->hasMany(Animal_Condicion::class);
    }
}
