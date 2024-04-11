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
        Schema::create('articulo_inventariado', function (Blueprint $table) {
            $table->string('id_inventario')->primary();
            $table->string('id_articulo')->nullable();
            $table->string('estatus');
            $table->string('tipo');
            $table->foreign('id_articulo')->references('id_articulo')->on('catalogo_articulo')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_inventariado');
    }
};
