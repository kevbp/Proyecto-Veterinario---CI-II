<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('fecha_hora');
            $table->text('motivo');
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->decimal('peso_registrado', 8, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignUuid('personal_id')->constrained('personal')->cascadeOnDelete();
            $table->foreignUuid('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
