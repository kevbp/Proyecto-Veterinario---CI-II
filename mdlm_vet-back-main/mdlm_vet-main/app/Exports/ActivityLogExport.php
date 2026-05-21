<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityLogExport implements FromQuery, WithHeadings
{
    public function __construct(private $fechaLimite) {}

    public function query()
    {
        // Logs más antiguos que la fecha límite
        return Activity::query()->where('created_at', '<', $this->fechaLimite);
    }

    // Títulos de las columnas del CSV
    public function headings(): array
    {
        return [
            'ID Log',
            'Categoría',
            'Descripción',
            'ID Registro Afectado',
            'Tipo de Registro',
            'ID Usuario Causante',
            'Tipo de Usuario',
            'Cambios (JSON)',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }
}