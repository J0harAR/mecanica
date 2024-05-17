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
        Schema::table('alumno_practica', function (Blueprint $table) {

            $table->date('fecha')->nullable();
            $table->integer('no_equipo')->nullable();
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumno_practica', function (Blueprint $table) {
            //
        });
    }
};
