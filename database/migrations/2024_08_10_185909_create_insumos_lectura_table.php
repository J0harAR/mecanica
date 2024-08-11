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
        Schema::create('insumos_lectura', function (Blueprint $table) {
            $table->id();
            $table->string('id_insumo')->nullable();
            $table->unsignedBigInteger('id_lectura')->nullable();;
            $table->float('cantidad')->nullable();
            $table->float('cantidad_anterior')->nullable();
            $table->float('cantidad_nueva')->nullable();
            $table->timestamps();


            $table->foreign('id_insumo')->references('id_articulo')->on('catalogo_articulo')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_lectura')->references('id')->on('lectura')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos_lectura');
    }
};
