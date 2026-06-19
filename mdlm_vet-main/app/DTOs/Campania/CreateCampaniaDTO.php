<?php

namespace App\DTOs\Campania;

class CreateCampaniaDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly string $lugar,
        public readonly string $fecha_hora_inicio,
        public readonly string $fecha_hora_fin,
        public readonly string $estado = 'planificada', // Valor por defecto
        public readonly string $responsable_id, // Jefe de Campaña (Personal)
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            lugar: $data['lugar'],
            fecha_hora_inicio: $data['fecha_hora_inicio'], // Valor por defecto a la fecha actual si no se proporciona
            fecha_hora_fin: $data['fecha_hora_fin'],
            estado: $data['estado'] ?? 'planificada', // Valor por defecto si no se proporciona
            responsable_id: $data['responsable_id'],
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
