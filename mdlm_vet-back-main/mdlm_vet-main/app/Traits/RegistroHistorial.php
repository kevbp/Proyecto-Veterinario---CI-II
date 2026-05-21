<?php

namespace App\Traits;

use App\Models\Historial;

trait RegistroHistorial
{
    protected static function bootRegistroHistorial()
    {
        static::created(function ($model) {
            // Cuando se cree algun objeto de modelo Consulta, Vacuna, Desparasitacion o Analisis,
            // se debe crear un registro en la tabla historial
            Historial::create([
                'animal_id' => $model->animal_id,
                'fecha_hora' => $model->fecha_hora ?? $model->created_at, // en caso de vacuna, seria la fecha_aplicacion...
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