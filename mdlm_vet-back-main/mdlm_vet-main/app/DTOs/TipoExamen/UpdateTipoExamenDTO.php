<?php

namespace App\DTOs\TipoExamen;

class UpdateTipoExamenDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly string $categoria,
        public readonly float $precio_ref,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'],
            nombre: $data['nombre'],
            categoria: $data['categoria'],
            precio_ref: (float) $data['precio_ref'],
        );
    }

    public function toArray(): array
    {
        return [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'precio_ref' => $this->precio_ref,
        ];
    }
}
