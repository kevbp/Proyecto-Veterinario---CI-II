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
        Schema::create('animal_condicion', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->onDelete('cascade');
            $table->foreignUuid('condicion_id')->constrained('catalogo_condiciones')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->date('fecha_diagnostico')->nullable();
            $table->enum('severidad', ['leve', 'moderado', 'severo'])->nullable();
            $table->enum('estado_clinico', ['activa', 'inactiva', 'resuelta'])->nullable();
            $table->foreignUuid('consulta_id')->nullable()->constrained('consultas')->nullOnDelete();
            $table->timestamps();

            // Índice para perfil del animal
            $table->index('animal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_condicion');
    }
};
