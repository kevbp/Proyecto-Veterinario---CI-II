<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linea_medicamentos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->foreignUuid('medicamento_id')->constrained('medicamentos')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->string('dosis');
            $table->string('frecuencia');
            $table->string('duracion');
            $table->string('instruccion_especifica')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linea_medicamentos');
    }
};
