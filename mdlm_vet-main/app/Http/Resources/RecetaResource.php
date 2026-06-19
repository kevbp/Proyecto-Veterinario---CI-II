<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecetaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'consulta_id' => $this->consulta_id,
            'estado_receta' => $this->estado_receta,
            'indicaciones_generales' => $this->indicaciones_generales,
            'fecha_emision' => $this->fecha_emision,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'lineas_medicamento' => LineaMedicamentoResource::collection($this->whenLoaded('lineasMedicamentos')),
        ];
    }
}
