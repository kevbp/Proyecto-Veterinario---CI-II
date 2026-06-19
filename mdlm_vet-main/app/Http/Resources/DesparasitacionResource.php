<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesparasitacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'medicamento' => new MedicamentoResource($this->whenLoaded('medicamento')),
            // 'animal_id' => $this->animal_id,
            // 'medicamento_id' => $this->medicamento_id,
            'fecha_aplicacion' => $this->fecha_aplicacion,
            'fecha_aplicacion_sgte' => $this->fecha_aplicacion_sgte,
            'dosis' => $this->dosis,
            'via' => $this->via,
            'observaciones' => $this->observaciones,
            'cantidad' => $this->cantidad,
            // 'campania_id' => $this->campania_id,
            // 'personal_id' => $this->personal_id,
            // 'consulta_id' => $this->consulta_id,
            
            // Relaciones
            'personal' => new PersonalResource($this->whenLoaded('personal')),
            'consulta' => $this->whenLoaded('consulta'),
            'campania' => new CampaniaResource($this->whenLoaded('campania')),
        ];
    }
}
