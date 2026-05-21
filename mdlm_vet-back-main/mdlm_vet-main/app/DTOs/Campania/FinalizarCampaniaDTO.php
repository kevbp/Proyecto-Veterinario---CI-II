<?php

namespace App\DTOs\Campania;

class FinalizarCampaniaDTO
{
    public function __construct(
        public readonly array $insumos_consumidos = [],
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            insumos_consumidos: $data['insumos_consumidos'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'insumos_consumidos' => $this->insumos_consumidos,
        ];
    }
}
