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
        Schema::create('citas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('fecha_hora');
            $table->text('motivo');
            $table->text('observaciones')->nullable();
            $table->foreignUuid('estado_cita_id')->constrained('estado_citas');
            $table->foreignUuid('animal_id')->constrained('animals');
            $table->foreignUuid('personal_id')->constrained('personal');
            $table->timestamps();

            // Índices de consulta frecuente
            $table->index('animal_id');
            $table->index('fecha_hora');
            $table->index('personal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
