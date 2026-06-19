<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[OA\Schema(
    schema: 'Personal',
    title: 'Personal',
    description: 'Modelo de personal de la veterinaria',
    required: [],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000', nullable: true),
        new OA\Property(property: 'tipo_doc_id', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: 12345678),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez', nullable: true),
        new OA\Property(property: 'email', type: 'string', example: 'juan.perez@example.com'),
        new OA\Property(property: 'celular', type: 'string', example: '999888777', nullable: true),
        new OA\Property(property: 'especialidad', type: 'string', example: 'Cardiología', nullable: true),
        new OA\Property(property: 'rol_sistema', type: 'string', example: 'veterinario'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Personal extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    protected $table = 'personal';

    protected $fillable = [
        'user_id',
        'tipo_doc_id',
        'nro_doc',
        'nombre',
        'paterno',
        'materno',
        'email',
        'celular',
        'especialidad',
        'rol_sistema',
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
        'especialidad' => 'string',
        'rol_sistema' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    /**
     * Indica si el personal ya fue vinculado a un usuario del SSO.
     */
    public function estaVinculado(): bool
    {
        return $this->user_id !== null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['invitation_accepted_at', 'user_id'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('Personal');
    }
}
