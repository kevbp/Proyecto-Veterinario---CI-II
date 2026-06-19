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
        Schema::create('examens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->foreignUuid('tipo_examen_id')->constrained('tipo_examens')->restrictOnDelete();
            $table->text('descripcion');
            $table->enum('estado', ['Pendiente', 'En Progreso', 'Completado'])->default('Pendiente');
            $table->timestamp('fecha_hora');
            $table->timestamp('fecha_resultado')->nullable();
            $table->foreignUuid('consulta_id')->nullable()->constrained('consultas')->nullOnDelete();
            $table->foreignUuid('animal_id')->nullable()->constrained('animals')->cascadeOnDelete();
            $table->timestamps();

            // Índices de consulta frecuente
            $table->index('animal_id');
            $table->index('consulta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
