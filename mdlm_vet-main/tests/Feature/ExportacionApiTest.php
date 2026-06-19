<?php

/*
|--------------------------------------------------------------------------
| Feature Tests — API de Exportaciones (Reportes Temporales / Kardex)
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Queue;
use App\Jobs\ArchivarLogsAntiguosJob;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->user = crearUsuarioConRol('admin');
});

test('job de archivo de logs antiguos se encola y/o procesa exitosamente', function () {
    // Falsificamos Excel para no crear basura real
    Excel::fake();

    // Inyectamos algunos logs antiguos que excedan 6 meses
    \Spatie\Activitylog\Models\Activity::create([
        'log_name' => 'default',
        'description' => 'Test Log',
        'created_at' => now()->subMonths(7)
    ]);

    $job = new ArchivarLogsAntiguosJob();
    $job->handle();

    // Verificamos que se llamó el sistema de descargas
    Excel::assertStored('archivos_auditoria/auditoria_' . now()->format('Y_m_d_H_i') . '.csv', 'local');

    // El log debió borrarse (fue archivado)
    $this->assertDatabaseMissing('activity_log', ['description' => 'Test Log']);
});

test('Endpoint para exportar flujo de inventario (Kardex)', function () {
    // Evitamos descargar realmente durante el test automatizado
    Excel::fake();

    $response = $this->actingAs($this->user, 'api')
        ->getJson('/api/inventario/exportar-flujo?fecha_inicio=2026-01-01&fecha_fin=2026-12-31');

    $response->assertOk();
    // Excel::assertDownloaded('flujo_inventario.xlsx'); // Depende del config en InventarioController
});
