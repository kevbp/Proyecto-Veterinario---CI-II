<?php

namespace App\DTOs\Personal;

class CreatePersonalDTO
{
    public function __construct(
        public readonly string $tipo_doc_id,
        public readonly int $nro_doc,
        public readonly string $nombre,
        public readonly string $paterno,
        public readonly ?string $materno,
        public readonly string $email,
        public readonly ?string $celular,
        public readonly ?string $especialidad,
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
