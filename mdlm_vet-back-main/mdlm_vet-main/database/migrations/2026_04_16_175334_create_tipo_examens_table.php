<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_examens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 255);
            $table->string('categoria', 255);
            $table->decimal('precio_ref', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_examens');
    }
};
