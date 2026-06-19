<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropietarioResource extends JsonResource
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
            'nombre' => $this->nombre,
            'paterno' => $this->paterno,
            'materno' => $this->materno,
            'nombre_completo' => "{$this->nombre} {$this->paterno} {$this->materno}",
            'email' => $this->email,
            'nro_doc' => $this->nro_doc,
            'tipo_doc' => $this->tipo_doc,
            'celular' => $this->celular,
            'nro_emergencia' => $this->nro_emergencia,
            'direccion' => $this->vivienda_direccion,
            'vivienda_latitud' => $this->vivienda_latitud,
            'vivienda_longitud' => $this->vivienda_longitud,
            'vinculado' => $this->estaVinculado(),
        ];
    }
}
