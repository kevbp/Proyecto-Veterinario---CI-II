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
        Schema::create('animal_alergia', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('alergia_id')->constrained('catalogo_alergias')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->enum('severidad', ['leve', 'moderado', 'severo'])->nullable();
            $table->enum('estado_clinico', ['activa', 'inactiva', 'resuelta'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_alergia');
    }
};
