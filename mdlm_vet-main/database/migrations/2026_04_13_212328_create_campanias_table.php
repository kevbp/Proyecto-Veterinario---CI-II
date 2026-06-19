<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campanias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('lugar')->nullable();
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->enum('estado', ['planificada', 'en_curso', 'finalizada', 'cancelada'])->default('planificada');
            $table->foreignUuid('responsable_id')->constrained('personal')->restrictOnDelete();
            $table->timestamps();

            // Índices para filtros frecuentes
            $table->index('estado');
            $table->index(['fecha_hora_inicio', 'fecha_hora_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanias');
    }
};
