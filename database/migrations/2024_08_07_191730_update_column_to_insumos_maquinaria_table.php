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
        Schema::table('insumos_maquinaria', function (Blueprint $table) {
            // Eliminar la clave foránea existente, si aplica
            $table->dropForeign(['insumo_id']);

            // Cambiar el tipo de columna insumo_id a string
            $table->string('insumo_id')->change();

            // Añadir la nueva clave foránea
            $table->foreign('insumo_id')->references('id_articulo')->on('catalogo_articulo')->onDelete('cascade')->onUpdate('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumos_maquinaria', function (Blueprint $table) {
            //
        });
    }
};
