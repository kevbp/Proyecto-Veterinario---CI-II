<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogExport;
use Spatie\Activitylog\Models\Activity;

class ArchivarLogsAntiguosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Maximo 3 intentos
    public $tries = 3;

    public function __construct()
    {
        // El constructor está vacío porque el Job se ejecutará por tiempo
    }

    public function handle(): void
    {
        // 1. Calculamos la fecha: Todo lo que tenga más de 6 meses
        $fechaLimite = now()->subMonths(6);
        // $fechaLimite = now()->addDays(1);
        
        // Verificamos si hay algo que borrar, para no crear CSVs vacíos
        $cantidadLogs = Activity::where('created_at', '<', $fechaLimite)->count();
        if ($cantidadLogs === 0) {
            return; 
        }

        // 2. Generamos el nombre del archivo (Ej: auditoria_2026_04.csv)
        $nombreArchivo = 'auditoria_' . now()->format('Y_m_d_H_i') . '.csv';

        // 3. Guardamos el CSV en el disco privado del servidor
        Excel::store(
            new ActivityLogExport($fechaLimite), 
            'archivos_auditoria/' . $nombreArchivo, 
            'local' // O 's3' si usaras Amazon AWS
        );

        // 4. (Opcional por ahora) Aquí podrías enviar un correo al Admin avisando que el archivo está listo.

        // 5. Finalmente, eliminamos los registros de la base de datos para liberar espacio
        Activity::where('created_at', '<', $fechaLimite)->delete();
    }
}