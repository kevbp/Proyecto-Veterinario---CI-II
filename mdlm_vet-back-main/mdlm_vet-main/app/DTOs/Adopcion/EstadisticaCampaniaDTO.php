<?php

namespace App\DTOs\Adopcion;

class EstadisticaCampaniaDTO
{
    public function __construct(
        public readonly string $campania_id
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            campania_id: $data['campania_id']
        );
    }

    public function toArray(): array
    {
        return [
            'campania_id' => $this->campania_id,
        ];
    }
}
