<?php

namespace App\DTOs\Personal;

class UpdatePersonalDTO
{
    public function __construct(
        public readonly ?string $tipo_doc_id = null,
        public readonly ?int $nro_doc = null,
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
