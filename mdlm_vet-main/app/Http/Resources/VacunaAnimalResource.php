<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacunaAnimalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_proxima' => $this->fecha_proxima,
            'dosis' => $this->dosis,
            'lote' => $this->lote,
            'fabricante' => $this->fabricante,
            'observaciones' => $this->observaciones,
            'cantidad' => $this->cantidad,
            
            // Relaciones
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'esquema_vacuna' => new EsquemaVacunaResource($this->whenLoaded('esquemaVacuna')),
            'medicamento' => new MedicamentoResource($this->whenLoaded('medicamento')),
            'personal' => new PersonalResource($this->whenLoaded('personal')),
            'consulta' => $this->whenLoaded('consulta'), // Podrías crear ConsultaResource si fuera necesario
            'campania' => $this->whenLoaded('campania'),
        ];
    }
}
