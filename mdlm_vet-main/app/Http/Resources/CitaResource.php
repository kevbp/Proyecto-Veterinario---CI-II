<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_hora' => $this->fecha_hora,
            'motivo' => $this->motivo,
            'observaciones' => $this->observaciones,
            'estado_cita_id' => $this->estado_cita_id,
            'animal_id' => $this->animal_id,
            'personal_id' => $this->personal_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relaciones
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'personal' => new PersonalResource($this->whenLoaded('personal')),
            'estadoCita' => $this->whenLoaded('estadoCita'),
        ];
    }
}
