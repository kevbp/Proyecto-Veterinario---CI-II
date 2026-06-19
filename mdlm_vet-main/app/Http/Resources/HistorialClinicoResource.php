<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistorialClinicoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'eventable_id' => $this->eventable_id,
            'fecha_hora' => $this->fecha_hora->toIso8601String(),
            'tipo_evento' => $this->obtenerTipoEventoLegible(),
            'detalles' => null,
        ];

        if ($this->eventable) {
            $data['detalles'] = match ($this->eventable_type) {
                'App\Models\Consulta' => [
                    'motivo' => $this->eventable->motivo,
                    'diagnostico' => $this->eventable->diagnostico,
                    'peso_registrado' => $this->eventable->peso_registrado,
                    'receta' => $this->eventable->recetas->map(function ($receta) {
                        return [
                            'estado_receta' => $receta->estado_receta,
                            'indicaciones_generales' => $receta->indicaciones_generales,
                            'fecha_emision' => $receta->fecha_emision,
                            'fecha_vencimiento' => $receta->fecha_vencimiento,
                            'lineas_medicamento' => $receta->lineasMedicamentos->map(function ($linea) {
                                return [
                                    'medicamento_id' => $linea->medicamento_id,
                                    'medicamento_nombre' => $linea->medicamento?->nombre ?? 'Desconocido',
                                    'cantidad' => $linea->cantidad,
                                    'instruccion_especifica' => $linea->instruccion_especifica,
                                ];
                            }),
                        ];
                    }),
                ],
                'App\Models\Desparasitacion' => [
                    'medicamento' => $this->eventable->medicamento?->nombre ?? 'N/A',
                    'dosis' => $this->eventable->dosis,
                    'via' => $this->eventable->via,
                    'observaciones' => $this->eventable->observaciones,
                    'fecha_aplicacion' => $this->eventable->fecha_aplicacion,
                    'fecha_aplicacion_sgte' => $this->eventable->fecha_aplicacion_sgte,
                ],
                'App\Models\VacunaAnimal' => [
                    'esquema_vacuna' => $this->eventable->esquemaVacuna?->nombre ?? 'N/A',
                    'fecha_aplicacion' => $this->eventable->fecha_aplicacion,
                    'fecha_proxima' => $this->eventable->fecha_proxima,
                    'nro_dosis' => $this->eventable->dosis,
                    'lote' => $this->eventable->lote,
                    'fabricante' => $this->eventable->fabricante,
                    'observaciones' => $this->eventable->observaciones,
                    'medicamento' => $this->eventable->medicamento?->nombre ?? null,
                    'consulta_id' => $this->eventable->consulta_id,
                ],
                'App\Models\Examen' => [
                    'nombre' => $this->eventable->nombre,
                    'descripcion' => $this->eventable->descripcion,
                    'estado' => $this->eventable->estado,
                    'fecha_solicitud' => $this->eventable->fecha_hora,
                    'fecha_resultado' => $this->eventable->fecha_resultado,
                    'resultado' => $this->eventable->resultado ? [
                        'hallazgos' => $this->eventable->resultado->hallazgos,
                        'valores' => $this->eventable->resultado->valores,
                        'observaciones' => $this->eventable->resultado->observaciones,
                        'interpretacion' => $this->eventable->resultado->interpretacion,
                    ] : null,
                ],
                default => [],
            };
        }

        return $data;
    }

    private function obtenerTipoEventoLegible(): string
    {
        return match ($this->eventable_type) {
            'App\Models\Consulta' => 'Consulta Médica',
            'App\Models\Desparasitacion' => 'Desparasitación',
            'App\Models\VacunaAnimal' => 'Vacunación',
            'App\Models\Examen' => 'Examen Médico',
            default => 'Evento Médico',
        };
    }
}
