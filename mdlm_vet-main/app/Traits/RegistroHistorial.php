<?php

namespace App\Traits;

use App\Models\Historial;

trait RegistroHistorial
{
    protected static function bootRegistroHistorial()
    {
        static::created(function ($model) {
            // Cuando se cree algún objeto de modelo Consulta, Vacuna, Desparasitacion o Examen,
            // se debe crear un registro en la tabla historial
            
            // Determinar el campo de fecha según el tipo de modelo
            $fechaHistorial = null;
            
            if (isset($model->fecha_hora)) {
                $fechaHistorial = $model->fecha_hora;
            } elseif (isset($model->fecha_aplicacion)) {
                // Para VacunaAnimal y Desparasitacion que usan fecha_aplicacion (DATE sin hora).
                // Combinamos la fecha con la hora actual para evitar que midnight UTC
                // provoque un desfase de día en zonas horarias negativas.
                $fechaHistorial = $model->fecha_aplicacion->copy()->setTimeFrom(now());
            } elseif (isset($model->fecha_solicitud)) {
                // Para Examen que usa fecha_solicitud
                $fechaHistorial = $model->fecha_solicitud;
            } else {
                // Fallback a created_at si ninguno de los anteriores existe
                $fechaHistorial = $model->created_at;
            }
            
            Historial::create([
                'animal_id' => $model->animal_id,
                'fecha_hora' => $fechaHistorial,
                'eventable_id' => $model->id,
                'eventable_type' => get_class($model),
            ]);
        });
    }

    public function historial()
    {
        return $this->morphOne(Historial::class, 'eventable');
    }
}