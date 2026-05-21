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
        Schema::create('vacuna_animals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('fecha_aplicacion');
            $table->date('fecha_proxima')->nullable();
            $table->string('dosis');
            $table->string('lote');
            $table->string('fabricante')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignUuid('animal_id')->constrained()->cascadeOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->foreignUuid('esquema_vacuna_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('medicamento_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('personal_id')->constrained('personal')->cascadeOnDelete();
            $table->foreignUuid('consulta_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('campania_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacuna_animals');
    }
};
