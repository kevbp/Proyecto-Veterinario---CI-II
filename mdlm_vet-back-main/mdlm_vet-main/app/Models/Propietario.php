<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
        new OA\Property(property: 'invitation_sent_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'invitation_accepted_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Propietario extends Model
{
    use HasUuids;

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
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
    ];

    protected $hidden = [
        'invitation_token',
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
        'invitation_sent_at' => 'datetime',
        'invitation_accepted_at' => 'datetime',
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

    public function isInvitationPending(): bool
    {
        return $this->invitation_token !== null
            && $this->invitation_accepted_at === null;
    }

    public function hasUser(): bool
    {
        return $this->user_id !== null;
    }

    public function isInvitationExpired(): bool
    {
        if (!$this->invitation_sent_at) {
            return true;
        }

        return $this->invitation_sent_at->addHours(72)->isPast();
    }
}
