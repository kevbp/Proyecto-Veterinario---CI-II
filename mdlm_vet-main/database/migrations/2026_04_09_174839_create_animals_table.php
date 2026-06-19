<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('propietario_id')->constrained('propietarios')->cascadeOnDelete();
            $table->string('nombre');
            $table->foreignUuid('especie_id')->constrained('especies');
            $table->foreignUuid('raza_id')->nullable()->constrained('razas')->nullOnDelete();
            $table->enum('sexo', ['Macho', 'Hembra']);
            $table->string('color')->nullable();
            $table->boolean('esterilizacion')->default(false);
            $table->boolean('fallecido')->default(false);
            $table->date('fecha_fallecimiento')->nullable();
            $table->timestamps();

            // Índice para búsqueda por propietario
            $table->index('propietario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
