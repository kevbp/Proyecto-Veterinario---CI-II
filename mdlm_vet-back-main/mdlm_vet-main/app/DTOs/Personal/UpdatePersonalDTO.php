<?php

namespace App\DTOs\Personal;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePersonalDTO',
    properties: [
        new OA\Property(property: 'tipo_doc_id', type: 'string', example: 'DNI'),
        new OA\Property(property: 'nro_doc', type: 'string', example: '12345678'),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'paterno', type: 'string', example: 'Perez'),
        new OA\Property(property: 'materno', type: 'string', example: 'Gomez'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'juan@example.com'),
        new OA\Property(property: 'celular', type: 'string', example: '123456789'),
        new OA\Property(property: 'especialidad', type: 'string', example: 'Veterinario'),
        new OA\Property(property: 'rol_sistema', type: 'string', example: 'veterinario'),
    ]
)]
class UpdatePersonalDTO
{
    public function __construct(
        public readonly ?string $tipo_doc_id = null,
        public readonly ?string $nro_doc = null,
        public readonly ?string $nombre = null,
        public readonly ?string $paterno = null,
        public readonly ?string $materno = null,
        public readonly ?string $email = null,
        public readonly ?string $celular = null,
        public readonly ?string $especialidad = null,
        public readonly ?string $rol_sistema = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            tipo_doc_id: $data['tipo_doc_id'] ?? null,
            nro_doc: $data['nro_doc'] ?? null,
            nombre: $data['nombre'] ?? null,
            paterno: $data['paterno'] ?? null,
            materno: $data['materno'] ?? null,
            email: $data['email'] ?? null,
            celular: $data['celular'] ?? null,
            especialidad: $data['especialidad'] ?? null,
            rol_sistema: $data['rol_sistema'] ?? null,
        );
    }

    public function toArray(): array
    {
        $arr = [];
        if ($this->tipo_doc_id !== null) $arr['tipo_doc_id'] = $this->tipo_doc_id;
        if ($this->nro_doc !== null) $arr['nro_doc'] = $this->nro_doc;
        if ($this->nombre !== null) $arr['nombre'] = $this->nombre;
        if ($this->paterno !== null) $arr['paterno'] = $this->paterno;
        if ($this->materno !== null) $arr['materno'] = $this->materno;
        if ($this->email !== null) $arr['email'] = $this->email;
        if ($this->celular !== null) $arr['celular'] = $this->celular;
        if ($this->especialidad !== null) $arr['especialidad'] = $this->especialidad;
        if ($this->rol_sistema !== null) $arr['rol_sistema'] = $this->rol_sistema;
        return $arr;
    }
}
