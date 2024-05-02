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
            $table->string('insumo_id', 191)->change();
            $table->string('maquinaria_id', 191)->change();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumos_maquinaria', function (Blueprint $table) {
            $table->string('insumo_id')->change();
            $table->string('maquinaria_id')->change();
           
        });
    }
};
