<?php

namespace App\DTOs\Consulta;

class UpdateConsultaDTO
{
    public function __construct(
        public readonly ?string $motivo = null,
        public readonly ?string $diagnostico = null,
        public readonly ?string $tratamiento = null,
        public readonly ?float $peso_registrado = null,
        public readonly ?string $observaciones = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            motivo: $data['motivo'] ?? null,
            diagnostico: $data['diagnostico'] ?? null,
            tratamiento: $data['tratamiento'] ?? null,
            peso_registrado: isset($data['peso_registrado']) ? (float) $data['peso_registrado'] : null,
            observaciones: $data['observaciones'] ?? null,
        );
    }

    public function toArray(): array
    {
        $arr = [];
        // Permite que sean nulos pero que sí se actualicen.
        $arr['motivo'] = $this->motivo;
        $arr['diagnostico'] = $this->diagnostico;
        $arr['tratamiento'] = $this->tratamiento;
        $arr['peso_registrado'] = $this->peso_registrado;
        $arr['observaciones'] = $this->observaciones;

        return $arr;
    }
}
