<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'ERP Veterinaria',
    description: 'API REST para el sistema de gestión veterinaria',
    contact: new OA\Contact(email: 'admin@veterinaria.test')
)]
#[OA\Server(
    url: 'http://veterinaria.test',
    description: 'Servidor Local'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Ingrese el token JWT obtenido en el login'
)]
abstract class Controller
{
    //
}
