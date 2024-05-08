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
        Schema::create('asignatura_grupo', function (Blueprint $table) {
            $table->id();
            $table->string('clave_grupo')->nullable();
            $table->string('clave_asignatura')->nullable();


            $table->foreign('clave_grupo')->references('clave')->on('grupo')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('clave_asignatura')->references('clave')->on('asignatura')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignatura_grupo');
    }
};
