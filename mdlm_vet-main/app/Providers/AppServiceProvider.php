<?php

namespace App\Providers;

use App\Services\AnimalAlergiaService;
use App\Services\AnimalCondicionService;
use App\Services\AnimalService;
use App\Services\CitaService;
use App\Services\ConsultaService;
use App\Services\DesparasitacionService;
use App\Services\EspecieService;
use App\Services\EsquemaVacunaService;
use App\Services\ExamenService;
use App\Services\HistorialService;
use App\Services\InstrumentoService;
use App\Services\LineaMedicamentoService;
use App\Services\MedicamentoService;
use App\Services\PersonalService;
use App\Services\PropietarioService;
use App\Services\RecetaService;
use App\Services\TipoExamenService;
use App\Services\UserService;
use App\Services\VacunaService;
use App\Services\RazaService;
use App\Services\ResultadoService;
use App\Services\CampaniaService;
use App\Services\InventarioService;
use App\Services\AdopcionService;
use App\Services\Contracts\AdopcionServiceInterface;
use App\Services\Contracts\InventarioServiceInterface;
use App\Services\Contracts\CampaniaServiceInterface;
use App\Services\Contracts\ExamenServiceInterface;
use App\Services\Contracts\AnimalAlergiaServiceInterface;
use App\Services\Contracts\AnimalCondicionServiceInterface;
use App\Services\Contracts\AnimalServiceInterface;
use App\Services\Contracts\CitaServiceInterface;
use App\Services\Contracts\ConsultaServiceInterface;
use App\Services\Contracts\DesparasitacionServiceInterface;
use App\Services\Contracts\EspecieServiceInterface;
use App\Services\Contracts\EsquemaVacunaServiceInterface;
use App\Services\Contracts\HistorialServiceInterface;
use App\Services\Contracts\InstrumentoServiceInterface;
use App\Services\Contracts\LineaMedicamentoServiceInterface;
use App\Services\Contracts\MedicamentoServiceInterface;
use App\Services\Contracts\PersonalServiceInterface;
use App\Services\Contracts\PropietarioServiceInterface;
use App\Services\Contracts\RecetaServiceInterface;
use App\Services\Contracts\TipoExamenServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\VacunaServiceInterface;
use App\Services\Contracts\RazaServiceInterface;
use App\Services\Contracts\ResultadoServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use App\Listeners\RegistrarLogin;
use Illuminate\Support\Facades\Auth;
use App\Listeners\VincularUsuarioAlSSO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InstrumentoServiceInterface::class, InstrumentoService::class);
        $this->app->bind(LineaMedicamentoServiceInterface::class, LineaMedicamentoService::class);
        $this->app->bind(MedicamentoServiceInterface::class, MedicamentoService::class);
        $this->app->bind(RecetaServiceInterface::class, RecetaService::class);
        $this->app->bind(PropietarioServiceInterface::class, PropietarioService::class);
        $this->app->bind(AnimalServiceInterface::class, AnimalService::class);
        $this->app->bind(EspecieServiceInterface::class, EspecieService::class);
        $this->app->bind(EsquemaVacunaServiceInterface::class, EsquemaVacunaService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(CitaServiceInterface::class, CitaService::class);
        $this->app->bind(PersonalServiceInterface::class, PersonalService::class);
        $this->app->bind(ConsultaServiceInterface::class, ConsultaService::class);
        $this->app->bind(HistorialServiceInterface::class, HistorialService::class);
        $this->app->bind(DesparasitacionServiceInterface::class, DesparasitacionService::class);
        $this->app->bind(VacunaServiceInterface::class, VacunaService::class);
        $this->app->bind(TipoExamenServiceInterface::class, TipoExamenService::class);
        $this->app->bind(RazaServiceInterface::class, RazaService::class);
        $this->app->bind(AnimalCondicionServiceInterface::class, AnimalCondicionService::class);
        $this->app->bind(AnimalAlergiaServiceInterface::class, AnimalAlergiaService::class);
        $this->app->bind(ExamenServiceInterface::class, ExamenService::class);
        $this->app->bind(ResultadoServiceInterface::class, ResultadoService::class);
        $this->app->bind(CampaniaServiceInterface::class, CampaniaService::class);
        $this->app->bind(InventarioServiceInterface::class, InventarioService::class);
        $this->app->bind(AdopcionServiceInterface::class, AdopcionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::subscribe(RegistrarLogin::class);

        Event::listen(
            'sso.user.authenticated',
            VincularUsuarioAlSSO::class
        );
        
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
