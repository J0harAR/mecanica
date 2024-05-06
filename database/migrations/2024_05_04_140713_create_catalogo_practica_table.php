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
        Schema::create('catalogo_practica', function (Blueprint $table) {
            $table->id();

            $table->string('practica_id')->nullable();
            $table->string('articulo_id')->nullable();


            $table->foreign('practica_id')->references('id_practica')->on('practica')->onUpdate('cascade');
            $table->foreign('articulo_id')->references('id_articulo')->on('catalogo_articulo')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_practica');
    }
};
