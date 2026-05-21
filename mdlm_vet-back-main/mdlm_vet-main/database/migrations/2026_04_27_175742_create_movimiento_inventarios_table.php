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
        Schema::create('movimiento_inventarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medicamento_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'merma', 'ajuste']);
            $table->decimal('cantidad_movimiento', 10, 2);
            $table->decimal('stock_anterior', 10, 2);
            $table->decimal('stock_actual', 10, 2);
            $table->text('motivo')->nullable();
            $table->uuid('referencia_id')->nullable();
            $table->string('referencia_tipo')->nullable();
            $table->foreignUuid('personal_id')->constrained('personal')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};
