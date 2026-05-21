<?php

namespace App\Services;

use App\Models\Historial;
use App\Services\Contracts\HistorialServiceInterface;


class HistorialService implements HistorialServiceInterface
{
    public function getTimelineByAnimalId(string $animalId)
    {
        return Historial::with('eventable')
            ->where('animal_id', $animalId)
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);
    }
}