<?php

namespace App\DTOs\VacunaAnimal;

class CreateVacunaDTO
{
    public function __construct(
        public string $fecha_aplicacion,
        public ?string $fecha_proxima,
        public string $dosis,
        public string $lote,
        public ?string $fabricante,
        public ?string $observaciones,
        public ?string $animal_id,
        public readonly float $cantidad,
        public string $esquema_vacuna_id,
        public string $medicamento_id,
        public string $personal_id,
        public readonly ?string $consulta_id = null,
        public readonly ?string $campania_id = null
    ) {}

    public static function fromRequest(array $data, string $personal_id, ?string $consulta_id, ?string $campania_id): self
    {
        return new self(
            fecha_aplicacion: $data['fecha_aplicacion'],
            fecha_proxima: $data['fecha_proxima'] ?? null,
            observaciones: $data['observaciones'] ?? null,
            dosis: $data['dosis'],
            
            lote: $data['lote'],
            fabricante: $data['fabricante'] ?? null,
            esquema_vacuna_id: $data['esquema_vacuna_id'],

            cantidad: (float) $data['cantidad'],
            personal_id: $personal_id,
            medicamento_id: $data['medicamento_id'],
            animal_id: $data['animal_id'] ?? null,
            consulta_id: $consulta_id ?? null,
            campania_id: $campania_id ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_proxima' => $this->fecha_proxima,
            'dosis' => $this->dosis,
            'lote' => $this->lote,
            'fabricante' => $this->fabricante,
            'observaciones' => $this->observaciones,
            'animal_id' => $this->animal_id,
            'cantidad' => $this->cantidad,
            'medicamento_id' => $this->medicamento_id,
            'esquema_vacuna_id' => $this->esquema_vacuna_id,
            'personal_id' => $this->personal_id,
            'consulta_id' => $this->consulta_id,
            'campania_id' => $this->campania_id,
        ];
    }
}
