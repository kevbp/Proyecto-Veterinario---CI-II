# Implementación de Filament v5 - Guía Completa

**Fecha:** Mayo 7, 2026  
**Proyecto:** ERP Veterinaria  
**Objetivo:** Integrar panel administrativo Filament sin afectar API REST existente

---

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Arquitectura General](#arquitectura-general)
3. [Instalación](#instalación)
4. [Configuración Inicial](#configuración-inicial)
5. [Protección y Seguridad](#protección-y-seguridad)
6. [Creación de Recursos](#creación-de-recursos)
7. [Integración con BD Existente](#integración-con-bd-existente)
8. [Estructura de Carpetas](#estructura-de-carpetas)
9. [Ejemplos Prácticos](#ejemplos-prácticos)
10. [Testing](#testing)
11. [Troubleshooting](#troubleshooting)

---

## 🎯 Requisitos Previos

### Versiones requeridas:
```
PHP: 8.2+
Laravel: 11.x
Filament: v5.0+
node: 18+
npm: 9+
```

### Paquetes existentes necesarios:
- ✅ `tymon/jwt-auth` (para autenticación API)
- ✅ `spatie/laravel-permission` (roles y permisos)
- ✅ `laravel/tinker` (opcional, para debugging)

### Verificar estado actual:
```bash
composer show | grep -E "tymon|spatie"
php artisan config:show auth
```

---

## 🏗️ Arquitectura General

### Separación de Guards

Tu proyecto actualmente usa dos guards diferentes:

```
┌─────────────────────────────────────────────────────┐
│               APLICACIÓN TOTAL                      │
├─────────────────────────┬───────────────────────────┤
│   API REST (JWT)        │   Filament Panel (Web)   │
├─────────────────────────┼───────────────────────────┤
│ Guard: api              │ Guard: web               │
│ Puerto: /api/*          │ Puerto: /admin           │
│ Auth: JWT Bearer Token  │ Auth: Session + Cookies  │
│ Usuarios: Todos         │ Usuarios: Solo Admins    │
├─────────────────────────┼───────────────────────────┤
│ Models:    User         │ Models: User (mismo)     │
│ Roles:     spatie       │ Roles:  spatie           │
│ BD:        compartida   │ BD:     compartida       │
└─────────────────────────┴───────────────────────────┘
```

### Flujo de Autenticación

```
Usuario API              Usuario Filament
    │                         │
    ├─ POST /api/auth/login   │
    │                         ├─ GET /admin
    │                         ├─ Redirect a /admin/login
    │  JWT Token              ├─ POST /admin/login
    │  (Bearer)               │
    │                         ├─ Session Cookie creada
    ├─ GET /api/usuarios      ├─ GET /admin/dashboard
    │  (Header: Bearer token) │  (Session válida)
    │                         │
    ↓                         ↓
```

---

## 🚀 Instalación

### PASO 1: Instalar Filament v5

```bash
# Ir a la raíz del proyecto
cd /ruta/a/veterinaria

# Instalar Filament usando composer
composer require filament/filament:"^5.0" -W

# Instalar demo (incluye tablas con datos de ejemplo)
php artisan filament:install --demo

# Alternativamente, instalación sin demo
# php artisan filament:install
```

### PASO 2: Publicar Assets

```bash
# Publicar archivos estáticos
php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --tag=filament-views

# Compilar frontend
npm install
npm run build
```

### PASO 3: Ejecutar Migraciones

```bash
# Filament no añade tablas nuevas, solo usa User existente
# Si la demo se instaló, ignorar este paso
php artisan migrate
```

### PASO 4: Crear Usuario Admin

```bash
# Opción A: Usar Tinker (interactivo)
php artisan tinker

# Dentro de Tinker:
>>> $user = User::first(); // O create si no existe
>>> $user->assignRole('admin');
>>> exit;

# Opción B: Crear seed específico (recomendado)
# Ver PASO 4B más adelante
```

### PASO 4B: Crear Seed para Usuario Admin (Recomendado)

Crear archivo `database/seeders/FilamentAdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FilamentAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@filament.local'],
            [
                'name' => 'Administrador Filament',
                'password' => bcrypt('admin123'), // CAMBIAR EN PRODUCCIÓN
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol admin si no lo tiene
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
```

Ejecutar:
```bash
php artisan db:seed --class=FilamentAdminSeeder
```

### PASO 5: Limpiar & Caché

```bash
# Limpiar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Regenerar autoload
composer dump-autoload

# Limpiar node modules (opcional si hay conflictos)
rm -rf node_modules
npm install
npm run build
```

---

## ⚙️ Configuración Inicial

### 1. Verificar `config/auth.php`

Asegurar que exista el guard `web`:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

### 2. Verificar `config/app.php`

Asegurar que esté el alias `Filament`:

```php
'aliases' => Facade::defaultAliases()
    ->merge([
        'Filament' => Filament\Facades\Filament::class,
    ]),
```

### 3. Configurar `config/filament/admin.php`

Crear/editar este archivo si no existe:

```php
<?php

return [
    'brand' => 'ERP Veterinaria',
    'logo_height' => 'auto',
    'dark_mode' => true,
    'favicon_url' => '/images/favicon.ico',
    
    'panel' => [
        'path' => 'admin',
        'middleware' => [
            'web',
            'auth',
            'role:admin', // Solo admins
        ],
    ],

    'navigation' => [
        'groups' => [
            'Gestión' => [
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
            'Clínico' => [
                'icon' => 'heroicon-o-heart',
            ],
            'Inventario' => [
                'icon' => 'heroicon-o-cube',
            ],
        ],
    ],

    'colors' => [
        'primary' => \Filament\Support\Colors\Color::Blue,
    ],

    'timezone' => env('APP_TIMEZONE', 'America/Lima'),
];
```

---

## 🔒 Protección y Seguridad

### 1. Middleware de Autenticación para Filament

Crear middleware en `app/Http/Middleware/FilamentAdminMiddleware.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilamentAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar que el usuario está autenticado
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        // Verificar que tiene rol admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo administradores pueden acceder.');
        }

        // Opcional: Log de acceso
        activity('Acceso Filament')
            ->causedBy(auth()->user())
            ->log('Accedió al panel administrativo');

        return $next($request);
    }
}
```

Registrar en `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... otros middlewares
    'filament.admin' => \App\Http\Middleware\FilamentAdminMiddleware::class,
];
```

### 2. Proteger Rutas en `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['web', 'auth', 'filament.admin'])->group(function () {
    // Filament se auto-registra aquí automáticamente
    // Rutas adicionales si las necesitas
    Route::get('/admin/diagnostico', [DashboardController::class, 'diagnostico'])
        ->name('admin.diagnostico');
});

// No proteger rutas de login (públicas)
Route::middleware(['web'])->group(function () {
    // Login ya manejado por Filament
});
```

### 3. Configurar Filament Provider

Crear/editar `app/Providers/FilamentServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Session\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\FilamentAdminMiddleware;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // \App\Filament\Widgets\AccountWidget::class,
                // \App\Filament\Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                HandlePrecognitiveRequests::class,
                FilamentAdminMiddleware::class, // ← Nuesto middleware
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

Registrar en `config/app.php`:

```php
'providers' => [
    // ... otros providers
    App\Providers\FilamentServiceProvider::class,
],
```

---

## 📊 Creación de Recursos

### ¿Qué es un Recurso Filament?

Un recurso es una interfaz CRUD completa para un modelo. Filament auto-genera:
- Listado con filtros y búsqueda
- Creación con validación
- Edición
- Eliminación

### PASO 1: Generar Recursos Automáticamente

```bash
# Para cada modelo importante:

# Animales
php artisan make:filament-resource Animal --generate

# Consultas
php artisan make:filament-resource Consulta --generate

# Usuarios
php artisan make:filament-resource User --generate

# Medicamentos
php artisan make:filament-resource Medicamento --generate

# Propietarios
php artisan make:filament-resource Propietario --generate

# Desparasitaciones
php artisan make:filament-resource Desparasitacion --generate

# Vacunas
php artisan make:filament-resource VacunaAnimal --generate

# Citas
php artisan make:filament-resource Cita --generate
```

Esto genera en `app/Filament/Resources/AnimalResource.php`, etc.

### PASO 2: Personalizar un Recurso (Ejemplo: AnimalResource)

Editar `app/Filament/Resources/AnimalResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimalResource\Pages;
use App\Models\Animal;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    // Icono en el menú
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    // Grupo en el menú
    protected static ?string $navigationGroup = 'Clínico';

    // Traducción singular
    protected static ?string $modelLabel = 'Animal';

    // Traducción plural
    protected static ?string $pluralModelLabel = 'Animales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),

                Select::make('propietario_id')
                    ->label('Propietario')
                    ->relationship('propietario', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('especie_id')
                    ->label('Especie')
                    ->relationship('especie', 'nombre')
                    ->required(),

                Select::make('raza_id')
                    ->label('Raza')
                    ->relationship('raza', 'nombre'),

                TextInput::make('peso')
                    ->label('Peso (kg)')
                    ->numeric()
                    ->step(0.1),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('propietario.nombre')
                    ->label('Propietario')
                    ->searchable(),

                TextColumn::make('especie.nombre')
                    ->label('Especie'),

                TextColumn::make('raza.nombre')
                    ->label('Raza'),

                TextColumn::make('peso')
                    ->label('Peso')
                    ->suffix(' kg'),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                // Agregar filtros aquí
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Agregar relaciones aquí
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimals::route('/'),
            'create' => Pages\CreateAnimal::route('/create'),
            'view' => Pages\ViewAnimal::route('/{record}'),
            'edit' => Pages\EditAnimal::route('/{record}/edit'),
        ];
    }
}
```

### PASO 3: Personalizar Páginas (ListAnimals)

Editar `app/Filament/Resources/AnimalResource/Pages/ListAnimals.php`:

```php
<?php

namespace App\Filament\Resources\AnimalResource\Pages;

use App\Filament\Resources\AnimalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimals extends ListRecords
{
    protected static string $resource = AnimalResource::class;

    // Encabezado de la página
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Animal'),
        ];
    }

    // Hook después de crear
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Animal creado exitosamente';
    }
}
```

---

## 🗄️ Integración con BD Existente

### PASO 1: Mapeo de Modelos

Tu BD ya existe, Filament usa los modelos existentes.

**No necesitas migraciones nuevas.**

Verificar que tus modelos tengan:

```php
// app/Models/Animal.php
class Animal extends Model
{
    use HasUuids; // Para UUID como PK
    
    protected $fillable = [
        'nombre',
        'propietario_id',
        'especie_id',
        // ... otros campos
    ];

    // Relaciones necesarias
    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Propietario::class);
    }

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }
}
```

### PASO 2: Configurar Casting de Datos

En los modelos, asegurar el casting correcto:

```php
protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
    'peso' => 'float',
    'animal_id' => 'string', // UUID
];
```

### PASO 3: Validación en Modelos (Optional)

Para reutilizar validaciones, crear `Rules`:

```bash
php artisan make:rule AnimalValidation
```

Usarlo en Filament:

```php
use App\Rules\AnimalValidation;

TextInput::make('nombre')
    ->rules([new AnimalValidation()])
    ->required(),
```

---

## 📁 Estructura de Carpetas

Después de la instalación, tu proyecto lucirá así:

```
app/
├── Filament/                          ← ✨ NUEVA CARPETA
│   ├── Resources/
│   │   ├── AnimalResource.php
│   │   ├── AnimalResource/
│   │   │   └── Pages/
│   │   │       ├── ListAnimals.php
│   │   │       ├── CreateAnimal.php
│   │   │       ├── ViewAnimal.php
│   │   │       └── EditAnimal.php
│   │   ├── ConsultaResource.php
│   │   ├── ConsultaResource/
│   │   │   └── Pages/...
│   │   ├── UserResource.php
│   │   └── ... (otros recursos)
│   ├── Pages/
│   │   └── Dashboard.php
│   ├── Widgets/
│   │   └── StatsOverview.php
│   └── Livewire/
│       └── ... (componentes custom)
│
├── Http/
│   ├── Controllers/                  ← Controllers API (sin cambios)
│   ├── Resources/                    ← Resources API (sin cambios)
│   └── Requests/                     ← Form Requests (sin cambios)
│
├── Models/                           ← Compartidos con Filament
│   ├── User.php
│   ├── Animal.php
│   ├── Consulta.php
│   └── ...
│
├── Providers/
│   ├── FilamentServiceProvider.php   ← ✨ NUEVO
│   └── ... (otros providers)
│
└── ...

routes/
├── api.php                           ← Sin cambios (JWT)
├── web.php                           ← Filament se registra aquí
└── console.php

config/
├── filament/
│   └── admin.php                     ← ✨ NUEVA CARPETA
└── ...

public/
├── admin/                            ← Assets compilados
└── ...
```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Crear Recurso para Usuarios

```bash
php artisan make:filament-resource User --generate
```

Personalizar `app/Filament/Resources/UserResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestión';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
                    ->required(fn(string $context) => $context === 'create'),

                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Toggle::make('email_verified_at')
                    ->label('Email Verificado')
                    ->visible(fn() => auth()->user()->hasRole('admin')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),

                BadgeColumn::make('roles.name')
                    ->label('Roles')
                    ->color(fn($state) => match($state) {
                        'admin' => 'danger',
                        'gestor' => 'warning',
                        'veterinario' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                // Filtro por rol
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
```

### Ejemplo 2: Crear Widget de Estadísticas

```bash
php artisan make:filament-widget StatsOverview --stats-overview
```

Crear `app/Filament/Widgets/StatsOverview.php`:

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Animal;
use App\Models\Consulta;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Animales', Animal::count())
                ->description('Animales en el sistema')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info'),

            Stat::make('Consultas Hoy', Consulta::whereDate('created_at', today())->count())
                ->description('Consultas realizadas hoy')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Usuarios Activos', User::where('email_verified_at', '!=', null)->count())
                ->description('Usuarios verificados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
```

---

## ✅ Testing

### PASO 1: Verificar que no afecta la API

```bash
# En terminal 1: Iniciar servidor
php artisan serve

# En terminal 2: Probar API
curl -X GET http://localhost:8000/api/condiciones \
  -H "Authorization: Bearer TU_JWT_TOKEN"

# Debería retornar datos sin problemas
```

### PASO 2: Verificar Filament

Acceder a: `http://localhost:8000/admin`

Deberías ver:
- ✅ Página de login (si no estás autenticado)
- ✅ Dashboard (si estás autenticado como admin)
- ✅ Menú con Recursos creados

### PASO 3: Pruebas Funcionales

```bash
# Crear test para Filament
php artisan make:test FilamentAccessTest

# Contenido:
<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FilamentAccessTest extends TestCase
{
    public function test_admin_can_access_filament()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_filament()
    {
        $user = User::factory()->create();
        $user->assignRole('veterinario');

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirectContains('/admin/login');
    }
}
```

Ejecutar:
```bash
php artisan test --filter=FilamentAccessTest
```

---

## 🐛 Troubleshooting

### Problema 1: "CORS Error"

**Síntoma:** API falla después de instalar Filament

**Solución:**
```php
// En routes/api.php, verificar que CORS middleware está presente
// No debería verse afectado porque Filament usa 'web' guard, no 'api'
```

### Problema 2: "Session not working in Filament"

**Síntoma:** No puedes loguear en Filament

**Solución:**
```php
// Verificar config/session.php
'driver' => env('SESSION_DRIVER', 'file'), // No 'array' en local

// Limpiar sesiones
php artisan session:table
php artisan migrate
```

### Problema 3: "Assets not loading (CSS/JS)"

**Síntoma:** Panel sin estilos

**Solución:**
```bash
# Recompilar assets
npm run build

# O usar watch en desarrollo
npm run dev

# Limpiar cache
php artisan vendor:publish --tag=filament-assets --force
```

### Problema 4: "Módulo no aparece en menú"

**Síntoma:** Crear recurso pero no aparece en sidebar

**Solución:**
```php
// En el Resource, agregar:
protected static ?string $navigationIcon = 'heroicon-o-heart';
protected static ?string $navigationGroup = 'Clínico';
protected static bool $shouldRegisterNavigation = true; // ← Asegurar esto
```

### Problema 5: "JWT Token invalida en Filament"

**Síntoma:** Si intenta usar JWT dentro de Filament

**Nota:** Filament usa `web` guard (sessions), no JWT. Los dos son independientes.

### Problema 6: "Tabla no sincroniza con cambios de BD"

**Síntoma:** Cambios en BD no se reflejan en Filament

**Solución:**
```php
// En modelo, asegurar fillable:
protected $fillable = ['nombre', 'email', ...];

// No usar $guarded = [];

// Limpiar cache
php artisan optimize:clear
```

### Problema 7: "Relaciones no cargan en recursos"

**Síntoma:** Select de relaciones vacío

**Solución:**
```php
// En Resource:
Select::make('animal_id')
    ->relationship('animal', 'nombre') // modelo, campo visible
    ->searchable()
    ->preload() // ← Cargar opciones al abrir
    ->required(),
```

---

## 📚 Comandos Útiles

```bash
# Generar recursos
php artisan make:filament-resource Animal --generate

# Generar página personalizada
php artisan make:filament-page Dashboard

# Generar widget
php artisan make:filament-widget StatsOverview

# Crear usuario admin
php artisan make:filament-user

# Limpiar todo y cachés
php artisan optimize:clear

# Publicar configuración
php artisan vendor:publish --tag=filament-config

# Ver rutas registradas por Filament
php artisan route:list | grep admin
```

---

## 🚀 Siguiente: Deploy en Producción

### Checklist Pre-Deploy:

- [ ] Cambiar contraseña admin por defecto
- [ ] Cambiar `APP_DEBUG=false` en `.env`
- [ ] Compilar assets: `npm run build`
- [ ] Ejecutar migraciones en BD producción
- [ ] Verificar permisos de carpetas `storage/` y `bootstrap/cache/`
- [ ] Habilitar HTTPS
- [ ] Configurar backups de BD
- [ ] Activar monitoreo de errores (Sentry, etc.)
- [ ] Restricción de IP para `/admin` (es opcional)

### Restricción IP para `/admin` (Nginx):

```nginx
location /admin {
    allow 192.168.1.0/24;  # Tu red interna
    deny all;
}
```

---

## 📖 Referencias Oficiales

- [Filament v5 Docs](https://filamentphp.com)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

---

## ✨ Conclusión

Con esta guía habrás integrado exitosamente **Filament v5** en tu proyecto sin afectar la API REST existente.

**Puntos clave:**
- 🔐 Seguridad: Guards separados (api + web)
- 📊 Datos: Misma BD, sin duplicación
- 👥 Autenticación: Reutiliza Spatie Permission
- 🎨 UI: Panel profesional para TI

**Próximos pasos opcionales:**
1. Crear dashboards personalizados
2. Agregar gráficas con Charts
3. Exportación a Excel/PDF
4. Auditoría detallada de cambios

---

**Versión:** 1.0  
**Actualizado:** Mayo 7, 2026  
**Autor:** Equipo de Desarrollo - ERP Veterinaria
