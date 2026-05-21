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
            $table->foreignUuid('tipo_doc_id')->constrained('tipo_documentos');
            $table->string('nro_doc');
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno')->nullable();
            
            $table->string('email')->unique();
            $table->string('celular')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('rol_sistema');

            // Invitation fields
            $table->string('invitation_token', 64)->nullable()->unique();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('invitation_accepted_at')->nullable();

            $table->timestamps();
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
