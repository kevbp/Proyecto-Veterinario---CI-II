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
        Schema::create('adopcions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('propietario_anterior_id')->constrained('propietarios')->restrictOnDelete();
            $table->foreignUuid('propietario_nuevo_id')->constrained('propietarios')->restrictOnDelete();
            $table->foreignUuid('campania_id')->nullable()->constrained('campanias')->nullOnDelete();
            $table->timestamp('fecha_adopcion');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índices para estadísticas
            $table->index('campania_id');
            $table->index('fecha_adopcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopcions');
    }
};
