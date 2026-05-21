<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;
use Illuminate\Events\Dispatcher;

class RegistrarLogin
{
    public function handleLogin(Login $event): void
    {
        $user = $event->user;

        activity('Seguridad')
            ->causedBy($user)
            ->withProperties(['ip' => Request::ip()])
            ->log('Inicio de sesión exitoso');
    }

    public function handleLogout(Logout $event): void
    {
        $user = $event->user;

        // En los cierres de sesión por expiración, el usuario a veces llega nulo. Lo verificamos.
        if (! $user) {
            return;
        }

        activity('Seguridad')
            ->causedBy($user)
            ->withProperties(['ip' => Request::ip()])
            ->log('Cierre de sesión exitoso');
    }

    // Mapeo de eventos por funcion
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [self::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [self::class, 'handleLogout']
        );
    }
}