<?php

namespace App\DTOs\Campania;

class UpdateCampaniaDTO
{
    public function __construct(
        public readonly ?string $nombre = null,
        public readonly ?string $descripcion = null,
        public readonly ?string $lugar = null,
        public readonly ?string $fecha_hora_inicio = null,
        public readonly ?string $fecha_hora_fin = null,
        public readonly ?string $estado = null,
        public readonly ?string $responsable_id = null, // Jefe de Campaña (Personal)
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre: $data['nombre'] ?? null,
            descripcion: $data['descripcion'] ?? null,
            lugar: $data['lugar'] ?? null,
            fecha_hora_inicio: $data['fecha_hora_inicio'] ?? null,
            fecha_hora_fin: $data['fecha_hora_fin'] ?? null,
            estado: $data['estado'] ?? null,
            responsable_id: $data['responsable_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'lugar' => $this->lugar,
            'fecha_hora_inicio' => $this->fecha_hora_inicio,
            'fecha_hora_fin' => $this->fecha_hora_fin,
            'estado' => $this->estado,
            'responsable_id' => $this->responsable_id,
        ];
    }
}
