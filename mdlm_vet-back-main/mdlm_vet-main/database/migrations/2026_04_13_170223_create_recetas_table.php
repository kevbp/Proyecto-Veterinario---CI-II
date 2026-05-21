<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('consulta_id')->constrained('consultas')->cascadeOnDelete();
            $table->string('estado_receta');
            $table->string('indicaciones_generales');
            $table->timestamp('fecha_emision');
            $table->timestamp('fecha_vencimiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
