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
        Schema::create('propietarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('tipo_doc_id')->constrained('tipo_documentos')->restrictOnDelete();
            $table->bigInteger('nro_doc')->unique();
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('celular')->nullable();
            $table->bigInteger('nro_emergencia')->nullable();
            $table->string('vivienda_direccion')->nullable();
            $table->decimal('vivienda_latitud', 10, 7)->nullable();
            $table->decimal('vivienda_longitud', 10, 7)->nullable();
            $table->timestamps();

            // Índices de búsqueda
            $table->index('user_id');
            $table->index('nro_doc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};
