<?php

namespace App\DTOs\Propietario;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePropietarioDTO',
    properties: [
        new OA\Property(property: 'tipo_doc', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: 12345678),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', example: 'example@example.com'),
        new OA\Property(property: 'celular', type: 'integer', example: 987654321),
        new OA\Property(property: 'nro_emergencia', type: 'integer', example: 987654321)
    ],
    type: 'object'
)]
class UpdatePropietarioDTO
{
    public function __construct(
        public readonly ?string $tipo_doc = null,
        public readonly ?int $nro_doc = null,
        public readonly ?string $nombre = null,
        public readonly ?string $paterno = null,
        public readonly ?string $materno = null,
        public readonly ?string $email = null,
        public readonly ?int $celular = null,
        public readonly ?int $nro_emergencia = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            tipo_doc: $data['tipo_doc'] ?? null,
            nro_doc: $data['nro_doc'] ?? null,
            nombre: $data['nombre'] ?? null,
            paterno: $data['paterno'] ?? null,
            materno: $data['materno'] ?? null,
            email: $data['email'] ?? null,
            celular: isset($data['celular']) ? (int) $data['celular'] : null,
            nro_emergencia: isset($data['nro_emergencia']) ? (int) $data['nro_emergencia'] : null,
        );
    }
    
    public function toArray(): array
    {
        return array_filter([
            'tipo_doc' => $this->tipo_doc,
            'nro_doc' => $this->nro_doc,
            'nombre' => $this->nombre,
            'paterno' => $this->paterno,
            'materno' => $this->materno,
            'email' => $this->email,
            'celular' => $this->celular,
            'nro_emergencia' => $this->nro_emergencia,
        ], fn($value) => !is_null($value));
    }
}
