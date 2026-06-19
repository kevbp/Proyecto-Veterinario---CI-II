<?php

namespace App\Services\Contracts;


interface HistorialServiceInterface
{
    public function getTimelineByAnimalId(string $animalId);
}
