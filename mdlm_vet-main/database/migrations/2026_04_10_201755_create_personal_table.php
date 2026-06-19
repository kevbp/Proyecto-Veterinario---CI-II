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
        Schema::create('personal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('tipo_doc_id')->constrained('tipo_documentos')->restrictOnDelete();
            $table->bigInteger('nro_doc')->unique();
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno')->nullable();
            $table->string('email')->nullable();
            $table->string('celular')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('rol_sistema');
            $table->timestamps();

            $table->index('user_id');
            $table->index('nro_doc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
