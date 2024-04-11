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
        Schema::create('asignatura_docente', function (Blueprint $table) {
            $table->foreignId('clave')->nullable();
            $table->foreignId('curp')->nullable();
            $table->string('clavePeriodo')->nullable();


            $table->foreignId('clave')->constrained();
            $table->foreignId('curp')->constrained();


            $table->foreign('clavePeriodo')->references('clave')->on('periodo')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignatura_docente');
    }
};
