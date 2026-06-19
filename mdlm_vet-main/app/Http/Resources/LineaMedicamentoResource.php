<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineaMedicamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'medicamento_id' => $this->medicamento_id,
            'medicamento' => new MedicamentoResource($this->whenLoaded('medicamento')),
            'cantidad' => $this->cantidad,
            'dosis' => $this->dosis,
            'frecuencia' => $this->frecuencia,
            'duracion' => $this->duracion,
            'instruccion_especifica' => $this->instruccion_especifica,
        ];
    }
}
