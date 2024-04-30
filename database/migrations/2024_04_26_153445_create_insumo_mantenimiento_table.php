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
        Schema::create('insumos_mantenimiento', function (Blueprint $table) {
            $table->id();
    

            $table->foreignId('insumo_id')->nullable();
            $table->foreignId('mantenimiento_id')->nullable();;
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos_mantenimiento');
    }
};
