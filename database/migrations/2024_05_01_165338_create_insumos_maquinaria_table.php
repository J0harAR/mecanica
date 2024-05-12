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
        Schema::create('insumos_maquinaria', function (Blueprint $table) {
            $table->id();
            
            $table->string('insumo_id')->nullable();
            $table->string('maquinaria_id')->nullable();


            $table->foreign('insumo_id')->references('id_insumo')->on('insumos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('maquinaria_id')->references('id_maquinaria')->on('maquinaria')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos_maquinaria');
    }
};
