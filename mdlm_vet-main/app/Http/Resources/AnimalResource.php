<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
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
            'propietario_id' => $this->propietario_id,
            'especie_id' => $this->especie_id,
            'raza_id' => $this->raza_id,
            'nombre' => $this->nombre,
            'sexo' => $this->sexo,
            'especie' => $this->whenLoaded('especie', fn () => $this->especie->nombre),
            'raza' => $this->whenLoaded('raza', fn () => $this->raza?->nombre),
            'peligroso' => $this->whenLoaded('raza', fn () => (bool) $this->raza?->peligroso, false),
            'color' => $this->color,
            'esterilizacion' => $this->esterilizacion,
            'propietario' => $this->whenLoaded('propietario', fn () => "{$this->propietario->nombre} {$this->propietario->paterno} {$this->propietario->materno}"),
            'propietario_celular' => $this->whenLoaded('propietario', fn () => $this->propietario->celular),
            'hogar' => $this->whenLoaded('propietario', fn () => $this->propietario->vivienda_direccion),
            'alergias' => $this->whenLoaded('alergias', fn () => $this->alergias->map(fn ($a) => [
                'id' => $a->id,
                'alergia' => $a->alergia?->nombre,
                'observaciones' => $a->observaciones,
                'severidad' => $a->severidad,
                'estado_clinico' => $a->estado_clinico,
            ])),
            'condiciones' => $this->whenLoaded('condiciones', fn () => $this->condiciones->map(fn ($c) => [
                'id' => $c->id,
                'condicion' => $c->condicion?->nombre,
                'observaciones' => $c->observaciones,
                'severidad' => $c->severidad,
                'fecha_diagnostico' => $c->fecha_diagnostico?->toDateString(),
                'estado_clinico' => $c->estado_clinico,
            ])),
            'fecha_registro' => $this->created_at?->toDateTimeString(),
            'fallecido' => $this->fallecido,
            'fecha_fallecimiento' => $this->fecha_fallecimiento?->toDateString(),
        ];
    }
}
