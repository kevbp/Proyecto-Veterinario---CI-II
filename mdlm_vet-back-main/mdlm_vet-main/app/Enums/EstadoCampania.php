<?php

namespace App\Enums;

enum EstadoCampania: string
{
    case PLANIFICADA = 'planificada';
    case EN_CURSO = 'en_curso';
    case FINALIZADA = 'finalizada';
    case CANCELADA = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::PLANIFICADA => 'Planificada',
            self::EN_CURSO => 'En Curso',
            self::FINALIZADA => 'Finalizada',
            self::CANCELADA => 'Cancelada',
        };
    }
}
