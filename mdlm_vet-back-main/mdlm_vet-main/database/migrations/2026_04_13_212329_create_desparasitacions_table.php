<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desparasitaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('medicamento_id')->constrained()->cascadeOnDelete();
            $table->date('fecha_aplicacion');
            $table->date('fecha_aplicacion_sgte');
            $table->string('dosis');
            $table->string('via');
            $table->text('observaciones')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->foreignUuid('personal_id')->constrained('personal')->cascadeOnDelete();
            $table->foreignUuid('consulta_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('campania_id')->nullable()->constrained('campanias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desparasitaciones');
    }
};
