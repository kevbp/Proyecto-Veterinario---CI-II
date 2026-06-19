<?php

namespace App\DTOs\Medicamento;

class UpdateMedicamentoDTO
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly ?string $descripcion,
        public readonly float $stock,
        public readonly ?string $estado,
        public readonly ?string $foto,
    ){}

    public static function fromRequest(array $data): self
    {
        return new self(
            codigo: $data['codigo'],
            nombre: $data['nombre'],
            descripcion: $data['descripcion'] ?? null,
            stock: $data['stock'],
            estado: $data['estado'] ?? null,
            foto: $data['foto'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'stock' => $this->stock,
            'estado' => $this->estado,
            'foto' => $this->foto,
        ];
    }
}
