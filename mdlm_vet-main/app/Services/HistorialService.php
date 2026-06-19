<?php

namespace App\Services;

use App\Models\Historial;
use App\Services\Contracts\HistorialServiceInterface;


class HistorialService implements HistorialServiceInterface
{
    public function getTimelineByAnimalId(string $animalId)
    {
        return Historial::with(['eventable' => function ($morphTo) {
            $morphTo->morphWith([
                \App\Models\Consulta::class => ['recetas.lineasMedicamentos.medicamento'],
                \App\Models\Desparasitacion::class => ['medicamento'],
                \App\Models\VacunaAnimal::class => ['esquemaVacuna', 'medicamento'],
            ]);
        }])
            ->where('animal_id', $animalId)
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);
    }
}
