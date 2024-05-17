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
        Schema::create('alumno_practica', function (Blueprint $table) {
            $table->string('alumno_id')->nullable();
            $table->string('practica_id')->nullable();
            
            $table->foreign('alumno_id')->references('no_control')->on('alumno')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('practica_id')->references('id_practica')->on('practica')->onUpdate('cascade')->onDelete('SET NULL');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_practica');
    }
};
