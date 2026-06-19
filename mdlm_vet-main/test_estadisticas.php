<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check actual column names
$columns_desp = \Illuminate\Support\Facades\Schema::getColumnListing('desparasitaciones');
echo "Columnas de desparasitaciones:" . PHP_EOL;
print_r($columns_desp);

$columns_vac = \Illuminate\Support\Facades\Schema::getColumnListing('vacuna_animals');
echo PHP_EOL . "Columnas de vacuna_animals:" . PHP_EOL;
print_r($columns_vac);
