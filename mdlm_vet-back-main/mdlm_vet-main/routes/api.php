<?php

use App\Http\Controllers\Auth\PersonalRegistrationController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AdopcionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalAlergiaController;
use App\Http\Controllers\CampaniaController;
use App\Http\Controllers\AnimalCondicionController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteRegistrationController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DesparasitacionController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\EsquemaVacunaController;
use App\Http\Controllers\EstadoCitaController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\InstrumentoController;
use App\Http\Controllers\LineaMedicamentoController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoExamenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacunaAnimalController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VistaPublicaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes (public)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    // Rutas públicas de invitación de cliente
    Route::get('invitacion/{token}', [ClienteRegistrationController::class, 'verify']);
    Route::post('registrar-cliente', [ClienteRegistrationController::class, 'register']);
    Route::post('registrar-personal', [PersonalRegistrationController::class, 'register']);
});

Route::prefix('public')->group(function () {
    
    // URL resultante: GET /api/public/campanias-activas
    Route::get('/campanias-activas', [VistaPublicaController::class, 'index']);
    
    // Si en el futuro tienes un catálogo de servicios público:
    // Route::get('/servicios', [CatalogoPublicoController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (protected)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {

    // --- Vista Cliente ---
    Route::middleware('role:propietario')->prefix('cliente')->group(function () {
        Route::get('perfil', [ClienteController::class, 'perfil']);
        Route::get('mascotas', [ClienteController::class, 'mascotas']);
        Route::get('mascotas/{id}', [ClienteController::class, 'mascota']);
        // Route::get('citas', [ClienteController::class, 'citas']); // Falta endpoints reales
        // Route::get('recetas', [ClienteController::class, 'recetas']); // Falta endpoints reales
    });

    // --- Gestión de Usuarios (admin, gestor) ---
    Route::get('usuarios/roles-asignables', [UserController::class, 'rolesAsignables'])->middleware('permission:ver usuarios');
    Route::apiResource('usuarios', UserController::class)->only(['index', 'show'])->middleware('permission:ver usuarios');
    Route::apiResource('usuarios', UserController::class)->only(['store'])->middleware('permission:crear usuarios');
    Route::apiResource('usuarios', UserController::class)->only(['update'])->middleware('permission:editar usuarios');
    Route::apiResource('usuarios', UserController::class)->only(['destroy'])->middleware('permission:eliminar usuarios');

    // --- Propietarios y Mascotas ---
    Route::post('propietarios/{id}/reenviar-invitacion', [PropietarioController::class, 'resendInvitation'])->middleware('permission:editar propietarios');
    Route::apiResource('propietarios', PropietarioController::class)->only(['index', 'show'])->middleware('permission:ver propietarios');
    Route::apiResource('propietarios', PropietarioController::class)->only(['store'])->middleware('permission:crear propietarios');
    Route::apiResource('propietarios', PropietarioController::class)->only(['update'])->middleware('permission:editar propietarios');
    Route::apiResource('propietarios', PropietarioController::class)->only(['destroy'])->middleware('permission:eliminar propietarios');

    Route::get('animales/{animal}/propietario', [AnimalController::class, 'getPropietario'])->middleware('permission:ver mascotas');
    Route::apiResource('animales', AnimalController::class)->parameters(['animales' => 'animal'])->only(['index', 'show'])->middleware('permission:ver mascotas');
    Route::apiResource('animales', AnimalController::class)->parameters(['animales' => 'animal'])->only(['store'])->middleware('permission:crear mascotas');
    Route::apiResource('animales', AnimalController::class)->parameters(['animales' => 'animal'])->only(['update'])->middleware('permission:editar mascotas');
    Route::apiResource('animales', AnimalController::class)->parameters(['animales' => 'animal'])->only(['destroy'])->middleware('permission:eliminar mascotas');

    // --- Tablas maestras ---
    // (Por ahora disponibles a quienes están autenticados)
    Route::get('raza/especie/{codigo}', [RazaController::class, 'getEspecieByCodigo']);
    Route::apiResource('raza', RazaController::class);
    Route::apiResource('esquema-vacunas', EsquemaVacunaController::class);
    Route::apiResource('especies', EspecieController::class);
    Route::get('tipo-documentos', [TipoDocumentoController::class, 'index']);
    Route::get('estado-citas', [EstadoCitaController::class, 'index']);

    //--- Adopciones ---
    Route::post('animales/{animal_id}/adopciones', [AdopcionController::class, 'setAdopcion'])->middleware('permission:editar mascotas');
    Route::get('adopciones/estadisticas-adopcion-por-campania', [AdopcionController::class, 'estadisticasAdopcionPorCampaña'])->middleware('permission:ver mascotas');
    Route::get('adopciones/estadisticas-adopcion-por-fechas', [AdopcionController::class, 'estadisticasAdopcionPorFechas'])->middleware('permission:ver mascotas');

    // --- Instrumentos ---
    Route::apiResource('instrumentos', InstrumentoController::class)->only(['index', 'show'])->middleware('permission:ver instrumentos');
    Route::apiResource('instrumentos', InstrumentoController::class)->only(['store'])->middleware('permission:crear instrumentos');
    Route::apiResource('instrumentos', InstrumentoController::class)->only(['update'])->middleware('permission:editar instrumentos');
    Route::apiResource('instrumentos', InstrumentoController::class)->only(['destroy'])->middleware('permission:eliminar instrumentos');

    // --- Medicamentos ---
    Route::patch('medicamentos/{id}/aumentar-stock', [MedicamentoController::class, 'aumentarStock'])->middleware('permission:editar medicamentos');
    Route::patch('medicamentos/{id}/restar-stock', [MedicamentoController::class, 'restarStock'])->middleware('permission:editar medicamentos');
    Route::apiResource('medicamentos', MedicamentoController::class)->only(['index', 'show'])->middleware('permission:ver medicamentos');
    Route::apiResource('medicamentos', MedicamentoController::class)->only(['store'])->middleware('permission:crear medicamentos');
    Route::apiResource('medicamentos', MedicamentoController::class)->only(['update'])->middleware('permission:editar medicamentos');
    Route::apiResource('medicamentos', MedicamentoController::class)->only(['destroy'])->middleware('permission:eliminar medicamentos');

    // --- Inventario y Kardex ---
    // Route::get('inventario/movimientos', [InventarioController::class, 'movimientos'])->middleware('permission:ver inventario');
    Route::post('inventario/ingreso-masivo', [InventarioController::class, 'ingresoMasivo'])->middleware('permission:crear inventario');
    Route::post('inventario/mermas', [InventarioController::class, 'registrarMermas'])->middleware('permission:crear inventario');
    Route::get('inventario/exportar-flujo', [InventarioController::class, 'exportarFlujoInventario'])->middleware('permission:ver inventario');

    // --- Recetas ---
    Route::get('recetas/{recetaId}/linea-medicamentos', [LineaMedicamentoController::class, 'getByReceta'])->middleware('permission:ver recetas');
    Route::apiResource('linea-medicamentos', LineaMedicamentoController::class)->only(['index', 'show'])->middleware('permission:ver recetas');
    Route::apiResource('linea-medicamentos', LineaMedicamentoController::class)->only(['store'])->middleware('permission:crear recetas');
    Route::apiResource('linea-medicamentos', LineaMedicamentoController::class)->only(['update'])->middleware('permission:editar recetas');
    Route::apiResource('linea-medicamentos', LineaMedicamentoController::class)->only(['destroy'])->middleware('permission:eliminar recetas');

    Route::apiResource('recetas', RecetaController::class)->only(['index', 'show'])->middleware('permission:ver recetas');
    Route::apiResource('recetas', RecetaController::class)->only(['store'])->middleware('permission:crear recetas');
    Route::apiResource('recetas', RecetaController::class)->only(['update'])->middleware('permission:editar recetas');
    Route::apiResource('recetas', RecetaController::class)->only(['destroy'])->middleware('permission:eliminar recetas');

    // --- Citas ---
    Route::apiResource('citas', CitaController::class)->only(['index', 'show'])->middleware('permission:ver citas');
    Route::apiResource('citas', CitaController::class)->only(['store'])->middleware('permission:crear citas');
    Route::apiResource('citas', CitaController::class)->only(['update'])->middleware('permission:editar citas');
    Route::apiResource('citas', CitaController::class)->only(['destroy'])->middleware('permission:eliminar citas');

    // --- Consultas ---
    Route::apiResource('consultas', ConsultaController::class)->only(['index', 'show'])->middleware('permission:ver consultas');
    Route::apiResource('consultas', ConsultaController::class)->only(['store'])->middleware('permission:crear consultas');
    Route::apiResource('consultas', ConsultaController::class)->only(['update'])->middleware('permission:editar consultas');
    Route::apiResource('consultas', ConsultaController::class)->only(['destroy'])->middleware('permission:eliminar consultas');

    // --- Historial Médico ---
    Route::get('animales/{animal_id}/historial', [HistorialController::class, 'getTimeline'])->middleware('permission:ver historiales');

    // --- Desparasitaciones ---
    Route::apiResource('desparasitaciones', DesparasitacionController::class)->only(['index', 'show'])->middleware('permission:ver desparasitaciones');
    Route::apiResource('desparasitaciones', DesparasitacionController::class)->only(['store'])->middleware('permission:crear desparasitaciones');
    Route::apiResource('desparasitaciones', DesparasitacionController::class)->only(['update'])->middleware('permission:editar desparasitaciones');
    Route::apiResource('desparasitaciones', DesparasitacionController::class)->only(['destroy'])->middleware('permission:eliminar desparasitaciones');

    // --- Vacunas Animales ---
    Route::apiResource('vacunas-animales', VacunaAnimalController::class)->only(['index', 'show'])->middleware('permission:ver vacunas');
    Route::apiResource('vacunas-animales', VacunaAnimalController::class)->only(['store'])->middleware('permission:crear vacunas');
    Route::apiResource('vacunas-animales', VacunaAnimalController::class)->only(['update'])->middleware('permission:editar vacunas');
    Route::apiResource('vacunas-animales', VacunaAnimalController::class)->only(['destroy'])->middleware('permission:eliminar vacunas');

    // --- Tipos de Examen ---
    Route::apiResource('tipos-examenes', TipoExamenController::class)->only(['index', 'show'])->middleware('permission:ver tipos-examenes');
    Route::apiResource('tipos-examenes', TipoExamenController::class)->only(['store'])->middleware('permission:crear tipos-examenes');
    Route::apiResource('tipos-examenes', TipoExamenController::class)->only(['update'])->middleware('permission:editar tipos-examenes');
    Route::apiResource('tipos-examenes', TipoExamenController::class)->only(['destroy'])->middleware('permission:eliminar tipos-examenes');

    // --- Exámenes ---
    Route::apiResource('examenes', ExamenController::class)->only(['index', 'show'])->middleware('permission:ver examenes');
    Route::apiResource('examenes', ExamenController::class)->only(['store'])->middleware('permission:crear examenes');
    Route::apiResource('examenes', ExamenController::class)->only(['update'])->middleware('permission:editar examenes');
    Route::apiResource('examenes', ExamenController::class)->only(['destroy'])->middleware('permission:eliminar examenes');

    // --- Razas ---
    Route::apiResource('razas', RazaController::class)->only(['index', 'show'])->middleware('permission:ver razas');
    Route::apiResource('razas', RazaController::class)->only(['store'])->middleware('permission:crear razas');
    Route::apiResource('razas', RazaController::class)->only(['update'])->middleware('permission:editar razas');
    Route::apiResource('razas', RazaController::class)->only(['destroy'])->middleware('permission:eliminar razas');

    // --- Condiciones ---
    Route::apiResource('condiciones', AnimalCondicionController::class)->only(['index', 'show'])->middleware('permission:ver condiciones');
    Route::apiResource('condiciones', AnimalCondicionController::class)->only(['store'])->middleware('permission:crear condiciones');
    Route::apiResource('condiciones', AnimalCondicionController::class)->only(['update'])->middleware('permission:editar condiciones');
    Route::apiResource('condiciones', AnimalCondicionController::class)->only(['destroy'])->middleware('permission:eliminar condiciones');

    // --- Alergias ---
    Route::apiResource('alergias', AnimalAlergiaController::class)->only(['index', 'show'])->middleware('permission:ver alergias');
    Route::apiResource('alergias', AnimalAlergiaController::class)->only(['store'])->middleware('permission:crear alergias');
    Route::apiResource('alergias', AnimalAlergiaController::class)->only(['update'])->middleware('permission:editar alergias');
    Route::apiResource('alergias', AnimalAlergiaController::class)->only(['destroy'])->middleware('permission:eliminar alergias');

    // --- Resultados de Exámenes ---
    Route::apiResource('resultados', ResultadoController::class)->only(['index', 'show'])->middleware('permission:ver resultados');
    Route::apiResource('resultados', ResultadoController::class)->only(['store'])->middleware('permission:crear resultados');
    Route::apiResource('resultados', ResultadoController::class)->only(['update'])->middleware('permission:editar resultados');
    Route::apiResource('resultados', ResultadoController::class)->only(['destroy'])->middleware('permission:eliminar resultados');

    // --- Campañas ---
    Route::get('campanias/{id}/estadisticas', [CampaniaController::class, 'estadisticas'])->middleware('permission:ver campanias');
    Route::patch('campanias/{id}/iniciar', [CampaniaController::class, 'iniciar'])->middleware('permission:editar campanias');
    Route::patch('campanias/{id}/cancelar', [CampaniaController::class, 'cancelar'])->middleware('permission:editar campanias');
    Route::post('campanias/{id}/finalizar', [CampaniaController::class, 'finalizar'])->middleware('permission:editar campanias');
    Route::apiResource('campanias', CampaniaController::class)->only(['index', 'show'])->middleware('permission:ver campanias');
    Route::apiResource('campanias', CampaniaController::class)->only(['store'])->middleware('permission:crear campanias');
    Route::apiResource('campanias', CampaniaController::class)->only(['update'])->middleware('permission:editar campanias');
    Route::apiResource('campanias', CampaniaController::class)->only(['destroy'])->middleware('permission:eliminar campanias');

    // --- Personal Administrativo y Médico ---
    Route::post('personal/{id}/reenviar-invitacion', [PersonalController::class, 'resendInvitation'])->middleware('permission:editar personal');
    Route::apiResource('personal', PersonalController::class)->only(['index', 'show'])->middleware('permission:ver personal');
    Route::apiResource('personal', PersonalController::class)->only(['store'])->middleware('permission:crear personal');
    Route::apiResource('personal', PersonalController::class)->only(['update'])->middleware('permission:editar personal');
    Route::apiResource('personal', PersonalController::class)->only(['destroy'])->middleware('permission:eliminar personal');
});
