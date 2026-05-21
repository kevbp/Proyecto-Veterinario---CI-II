# API REST para el Sistema de Gestión de Farmacia Veterinaria

## Pasos a seguir

1. Crear la base de datos 'veterinaria'
2. Crear el archivo .env y copiar el contenido del archivo .env.example
3. Configurar el archivo .env con los datos de la base de datos
4. Ejecutar 
```
composer install
```
5. Ejecutar 
```
php artisan key:generate
```
6. Ejecutar 
```
php artisan migrate --seed --seeder=DatabaseSeeder
```
7. Ejecutar 
```
php artisan jwt:secret
```
8. Correr 
```
php artisan serve
```
o usar Laravel HERD

## Documentación Swagger

Agregar /api/documentation al final de la URL del servidor