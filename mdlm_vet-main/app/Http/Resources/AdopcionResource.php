<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
 
class AdopcionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'propietario_anterior' => new PropietarioResource($this->whenLoaded('propietarioAnterior')),
            'propietario_nuevo' => new PropietarioResource($this->whenLoaded('propietarioNuevo')),
            'fecha_adopcion' => $this->fecha_adopcion,
            'observaciones' => $this->observaciones,
            'campania' => $this->whenLoaded('campania'),
        ];
    }
}
