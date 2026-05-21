<?php

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'CampaniaPublicaResource',
    description: 'Recurso que representa una campaña de adopción para la vista pública.',
    type: 'object',
    properties: [
        new OA\Property(property: 'nombre', type: 'string', description: 'Nombre de la campaña'),
        new OA\Property(property: 'descripcion', type: 'string', description: 'Descripción de la campaña'),
        new OA\Property(property: 'lugar', type: 'string', description: 'Lugar donde se realiza la campaña'),
        new OA\Property(property: 'fecha_inicio', type: 'string', format: 'date-time', description: 'Fecha y hora de inicio de la campaña'),
        new OA\Property(property: 'fecha_fin', type: 'string', format: 'date-time', description: 'Fecha y hora de fin de la campaña'),
        new OA\Property(property: 'estado', type: 'string', description: 'Estado actual de la campaña (planificada, en_curso, finalizada)'),
    ]
)]
class CampaniaPublicaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'nombre'       => $this->nombre,
            'descripcion'  => $this->descripcion,
            'lugar'        => $this->lugar,
            // Formateamos las fechas para que el frontend no tenga que procesarlas
            'fecha_inicio' => $this->fecha_hora_inicio->format('d/m/Y h:i A'),
            'fecha_fin'    => $this->fecha_hora_fin->format('d/m/Y h:i A'),
            'estado'       => $this->estado,
        ];
    }
}
