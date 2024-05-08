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
        Schema::create('alumno_grupo', function (Blueprint $table) {
            $table->id();

            $table->string('id_alumno')->nullable();
            $table->string('clave_grupo')->nullable();


            $table->foreign('id_alumno')->references('no_control')->on('alumno')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('clave_grupo')->references('clave')->on('grupo')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_grupo');
    }
};
