<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ArchivarLogsAntiguosJob;

// Ejecuta el Job el primer día de cada mes a las 3:00 AM
Schedule::job(new ArchivarLogsAntiguosJob)->monthlyOn(1, '03:00');

// Opción B: Para pruebas inmediadatas, ejecuta el Job cada minuto
//Schedule::job(new ArchivarLogsAntiguosJob)->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
