<?php

namespace App\Enums;

enum TipoMovimientoInventario: string
{
    case ENTRADA = 'entrada';
    case SALIDA = 'salida';
    case MERMA = 'merma';
    case AJUSTE = 'ajuste';

    public function label(): string
    {
        return match ($this) {
            self::ENTRADA => 'Entrada',
            self::SALIDA => 'Salida',
            self::MERMA => 'Merma',
            self::AJUSTE => 'Ajuste',
        };
    }
}
