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
        Schema::create('insumo_mantenimiento', function (Blueprint $table) {
            $table->id();
    

            $table->foreignId('id_insumo')->nullable();
            $table->foreignId('id_mantenimiento')->nullable();;
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumo_mantenimiento');
    }
};
