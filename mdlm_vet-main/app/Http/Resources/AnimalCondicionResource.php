<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalCondicionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'condicion' => new CatalogoCondicionesResource($this->whenLoaded('condicion')),
            'observaciones' => $this->observaciones,
            'fecha_diagnostico' => $this->fecha_diagnostico ? $this->fecha_diagnostico->toDateString() : null,
            'estado_clinico' => $this->estado_clinico,
            'consulta_id' => $this->consulta_id,
        ];
    }
}
