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
        Schema::create('inventariado_practica', function (Blueprint $table) {

            $table->string('practica_id')->nullable();
            $table->string('inventario_id')->nullable();

            $table->foreign('practica_id')->references('id_practica')->on('practica')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id_inventario')->on('articulo_inventariado')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventariado_practica');
    }
};
