<?php

namespace App\DTOs\LineaMedicamento;

class UpdateLineaMedicamentoDTO
{
    public function __construct(
        public readonly string $medicamento_id,
        public readonly float $cantidad,
        public readonly string $dosis,
        public readonly string $frecuencia,
        public readonly string $duracion,
        public readonly ?string $instruccion_especifica = null,
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            medicamento_id: $data['medicamento_id'],
            cantidad: (float) $data['cantidad'],
            dosis: $data['dosis'],
            frecuencia: $data['frecuencia'],
            duracion: $data['duracion'],
            instruccion_especifica: $data['instruccion_especifica'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'medicamento_id' => $this->medicamento_id,
            'cantidad' => $this->cantidad,
            'dosis' => $this->dosis,
            'frecuencia' => $this->frecuencia,
            'duracion' => $this->duracion,
            'instruccion_especifica' => $this->instruccion_especifica
        ];
    }
}
