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
        Schema::create('herramientas', function (Blueprint $table) {

            $table->string('id_herramientas')->primary();
            $table->foreign('id_herramientas')->references('id_inventario')->on('articulo_inventariado');
            $table->string('condicion');
            $table->float('dimension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('herramientas');
    }
};
