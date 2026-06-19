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

Agregar `/api/documentation` al final de la URL del servidor.

## Gestión de Logs (Limpieza y Exportación)

El sistema genera registros de auditoría sobre las acciones de los usuarios en la BD. Como buena práctica y para evitar la saturación de la base de datos, existe una tarea programada (`app/Jobs/ArchivarLogsAntiguosJob.php`) que exporta y limpia los registros antiguos.

**¿Qué hace el proceso?**
1. Calcula la fecha límite (registros con más de 6 meses de antigüedad).
2. Genera un archivo CSV temporal usando Maatwebsite Excel (`ActivityLogExport`).
3. Guarda el informe CSV bajo la carpeta `storage/app/archivos_auditoria/`.
4. Elimina permanentemente los registros de la base de datos menores a esa fecha.

Al tratarse de una tarea pesada, es una buena práctica delegarla al sistema de Colas (Queues) de Laravel para que se ejecute en segundo plano. Para ello, necesitas dos terminales separadas:

**Terminal 1: Encender el Worker (El obrero en segundo plano)**
Esta terminal se quedará escuchando y procesando los Jobs a medida que lleguen.
```bash
php artisan queue:work
```

**Terminal 2: Encolar la tarea**
Aquí entraremos a la consola interactiva de Laravel (Tinker) para decirle al sistema que agregue la tarea de limpieza a la cola.
```bash
php artisan tinker
```
Dentro de Tinker, encola la tarea usando la clase Queue para evitar bloqueos interactivos, y presiona Enter:
```php
\Illuminate\Support\Facades\Queue::push(new \App\Jobs\ArchivarLogsAntiguosJob());
```
Verás un identificador numérico o nulo en esta consola interactiva, pero en la **Terminal 1** verás que el Job inicia y termina su procesamiento ("Processing / Processed").

**Nota final:** 
Una vez que el Job finalice (en la Terminal 1), podrás encontrar el archivo CSV con la información filtrada accediendo a tu proyecto bajo el directorio: `storage/app/archivos_auditoria/`.