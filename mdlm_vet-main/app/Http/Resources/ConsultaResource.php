<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_hora' => $this->fecha_hora,
            'motivo' => $this->motivo,
            'diagnostico' => $this->diagnostico,
            'tratamiento' => $this->tratamiento,
            'peso_registrado' => $this->peso_registrado,
            'observaciones' => $this->observaciones,
            'animal_id' => $this->animal_id,
            'personal_id' => $this->personal_id,
            'cita_id' => $this->cita_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relaciones
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'personal' => new PersonalResource($this->whenLoaded('personal')),
            'cita' => new CitaResource($this->whenLoaded('cita')),
            'recetas' => RecetaResource::collection($this->whenLoaded('recetas')),
        ];
    }
}
