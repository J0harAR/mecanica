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
        Schema::table('docente_grupo', function (Blueprint $table) {
            $table->string('clave_asignatura')->nullable();
            $table->string('clave_periodo')->nullable();
        

            $table->foreign('clave_asignatura')->references('clave')->on('asignatura')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('clave_periodo')->references('clave')->on('periodo')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docente_grupo', function (Blueprint $table) {
            //
        });
    }
};
