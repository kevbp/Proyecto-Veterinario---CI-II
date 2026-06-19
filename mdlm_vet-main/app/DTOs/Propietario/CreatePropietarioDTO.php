<?php

namespace App\DTOs\Propietario;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreatePropietarioDTO',
    required: ['tipo_doc', 'nro_doc', 'nombre', 'paterno', 'email', 'celular', 'nro_emergencia'],
    properties: [
        new OA\Property(property: 'tipo_doc', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'integer', example: 12345678),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', example: 'example@example.com'),
        new OA\Property(property: 'celular', type: 'integer', example: 987654321),
        new OA\Property(property: 'nro_emergencia', type: 'integer', example: 987654321),
        new OA\Property(property: 'vivienda_direccion', type: 'string', nullable: true, example: 'Av. La Molina 123, Lima, Perú'),
        new OA\Property(property: 'vivienda_latitud', type: 'number', format: 'double', nullable: true, example: -12.0773588),
        new OA\Property(property: 'vivienda_longitud', type: 'number', format: 'double', nullable: true, example: -76.9438497),
    ],
    type: 'object'
)]
class CreatePropietarioDTO
{
    public function __construct(
        public readonly string $tipo_doc,
        public readonly int $nro_doc,
        public readonly string $nombre,
        public readonly string $paterno,
        public readonly ?string $materno = null,
        public readonly string $email = '',
        public readonly ?int $celular = null,
        public readonly ?int $nro_emergencia = null,
        public readonly ?string $vivienda_direccion = null,
        public readonly ?float $vivienda_latitud = null,
        public readonly ?float $vivienda_longitud = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            tipo_doc: $data['tipo_doc'],
            nro_doc: (int) $data['nro_doc'],
            nombre: $data['nombre'],
            paterno: $data['paterno'],
            materno: $data['materno'] ?? null,
            email: $data['email'],
            celular: isset($data['celular']) ? (int) $data['celular'] : null,
            nro_emergencia: isset($data['nro_emergencia']) ? (int) $data['nro_emergencia'] : null,
            vivienda_direccion: $data['vivienda_direccion'] ?? null,
            vivienda_latitud: isset($data['vivienda_latitud']) ? (float) $data['vivienda_latitud'] : null,
            vivienda_longitud: isset($data['vivienda_longitud']) ? (float) $data['vivienda_longitud'] : null,
        );
    }
    
    public function toArray(): array
    {
        return [
            'tipo_doc' => $this->tipo_doc,
            'nro_doc' => $this->nro_doc,
            'nombre' => $this->nombre,
            'paterno' => $this->paterno,
            'materno' => $this->materno,
            'email' => $this->email,
            'celular' => $this->celular,
            'nro_emergencia' => $this->nro_emergencia,
            'vivienda_direccion' => $this->vivienda_direccion,
            'vivienda_latitud' => $this->vivienda_latitud,
            'vivienda_longitud' => $this->vivienda_longitud,
        ];
    }
}
