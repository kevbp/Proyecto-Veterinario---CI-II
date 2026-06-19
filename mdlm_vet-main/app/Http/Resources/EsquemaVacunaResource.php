<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EsquemaVacunaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'enfermedad' => $this->enfermedad,
            'dosis' => $this->dosis,
            'intervalo_dias' => $this->intervalo_dias,
            'descripcion' => $this->descripcion,
            'especie' => new EspecieResource($this->whenLoaded('especie')),
        ];
    }
}
