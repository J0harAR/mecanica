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
        Schema::create('practica', function (Blueprint $table) {
            $table->string('id_practica')->primary();
            $table->string('id_docente');
            $table->string('nombre');
            $table->text('objetivo');
            $table->text('introduccion');
            $table->text('fundamento');
            $table->text('referencias');
            $table->boolean('estatus');


            $table->foreign('id_docente')->references('rfc')->on('docente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practica');
    }
};
