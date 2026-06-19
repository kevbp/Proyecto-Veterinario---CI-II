<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SSO Base URL
    |--------------------------------------------------------------------------
    | Aquí mapeamos la variable del .env para que el paquete la pueda leer.
    |
    */
    'jwks_ttl' => env('SSO_JWKS_TTL', 86400), // Tiempo de cacheo del JWKS en segundos (default: 24 horas)
    'url' => env('SSO_URL', 'http://sso.test'),
    'system_slug' => env('SYSTEM_SLUG', 'app satelite'),
    'jwks_path' => env('SSO_JWKS_PATH', '/.well-known/jwks.json'),
    'leeway' => env('SSO_JWT_LEEWAY', 60),
];