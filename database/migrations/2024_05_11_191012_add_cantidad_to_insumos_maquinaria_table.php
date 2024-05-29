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
            $table->float('capacidad')->nullable();
            $table->float('cantidad_actual')->nullable();
            $table->float('cantidad_minima')->nullable();
            
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
