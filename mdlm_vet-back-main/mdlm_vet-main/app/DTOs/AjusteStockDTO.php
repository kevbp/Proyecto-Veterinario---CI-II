<?php

namespace App\DTOs;

readonly class AjusteStockDTO
{
    public function __construct(
        public string $medicamento_id,
        public float $cantidad
    ){}
}