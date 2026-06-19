<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nro_doc' => $this->nro_doc,
            'nombre' => $this->nombre,
            'paterno' => $this->paterno,
            'materno' => $this->materno,
            'nombre_completo' => trim("{$this->nombre} {$this->paterno} {$this->materno}"),
            'email' => $this->email,
            'celular' => $this->celular,
            'profesion' => $this->especialidad, // Mapeamos especialidad a profesion para el front
            'rol_sistema' => $this->rol_sistema,
            'user' => $this->whenLoaded('user') ? new UserResource($this->user) : null,
        ];
    }
}
