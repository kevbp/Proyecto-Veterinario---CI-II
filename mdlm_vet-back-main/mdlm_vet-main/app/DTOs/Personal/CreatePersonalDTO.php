<?php

namespace App\DTOs\Personal;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreatePersonalDTO',
    required: ['tipo_doc_id', 'nro_doc', 'nombre', 'paterno', 'materno', 'email', 'celular', 'especialidad', 'rol_sistema'],
    properties: [
        new OA\Property(property: 'tipo_doc_id', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'string', example: '12345678'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: ''),
        new OA\Property(property: 'celular', type: 'string', example: '123456789'),
        new OA\Property(property: 'especialidad', type: 'string', example: 'Veterinario'),
        new OA\Property(property: 'rol_sistema', type: 'string', example: 'veterinario'),
    ]
)]
class CreatePersonalDTO
{
    public function __construct(
        public readonly string $tipo_doc_id,
        public readonly string $nro_doc,
        public readonly string $nombre,
        public readonly string $paterno,
        public readonly string $materno,
        public readonly string $email,
        public readonly string $celular,
        public readonly string $especialidad,
        public readonly string $rol_sistema,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            tipo_doc_id: $data['tipo_doc_id'],
            nro_doc: $data['nro_doc'],
            nombre: $data['nombre'],
            paterno: $data['paterno'],
            materno: $data['materno'],
            email: $data['email'],
            celular: $data['celular'],
            especialidad: $data['especialidad'],
            rol_sistema: $data['rol_sistema'],
        );
    }

    public function toArray(): array
    {
        return [
            'tipo_doc_id' => $this->tipo_doc_id,
            'nro_doc' => $this->nro_doc,
            'nombre' => $this->nombre,
            'paterno' => $this->paterno,
            'materno' => $this->materno,
            'email' => $this->email,
            'celular' => $this->celular,
            'especialidad' => $this->especialidad,
            'rol_sistema' => $this->rol_sistema,
        ];
    }
}
