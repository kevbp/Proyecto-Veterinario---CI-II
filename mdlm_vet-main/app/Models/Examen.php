<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use App\Traits\RegistroHistorial;

#[OA\Schema(
    schema: 'Examen',
    title: 'Examen',
    description: 'Modelo de examen',
    required: ['nombre', 'codigo', 'estado', 'fecha_solicitud', 'tipo_examen_id', 'descripcion'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Hemograma'),
        new OA\Property(property: 'tipo_examen_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'descripcion', type: 'string', example: 'Examen completo de sangre'),
        new OA\Property(property: 'estado', type: 'string', example: 'Pendiente'),
        new OA\Property(property: 'fecha_solicitud', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'fecha_resultado', type: 'string', format: 'date-time', example: '2024-06-01T10:00:00Z'),
        new OA\Property(property: 'consulta_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Examen extends Model
{
    use HasUuids, LogsActivity, RegistroHistorial;    

    protected $fillable = [
        'nombre',
        'tipo_examen_id',
        'descripcion',
        'estado',
        'fecha_hora',
        'fecha_resultado',
        'consulta_id',
        'animal_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'fecha_resultado' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Solo guarda los cambios en los campos especificados
            ->logOnly(['estado', 'fecha_resultado'])

            // ¡CRÍTICO PARA EL RENDIMIENTO! Solo guarda si los datos realmente cambiaron
            ->logOnlyDirty()

            // No guardes un registro vacío si el usuario le dio "Guardar" sin cambiar nada
            ->dontLogEmptyChanges()

            // (Opcional) Ponle un nombre legible al log
            ->useLogName('Mascotas');
    }

    public function tipoExamen()
    {
        return $this->belongsTo(TipoExamen::class, 'tipo_examen_id');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }

    public function resultado()
    {
        return $this->hasOne(Resultado::class);
    }
}