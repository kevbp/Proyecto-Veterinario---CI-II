<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalAlergiaResource extends JsonResource
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
            'alergia' => new CatalogoAlergiasResource($this->whenLoaded('alergia')),
            'observaciones' => $this->observaciones,
            'severidad' => $this->severidad,
            'estado_clinico' => $this->estado_clinico,
        ];
    }
}
