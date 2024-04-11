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
        Schema::create('catalogo_articulo', function (Blueprint $table) {
            $table->string('id_articulo')->primary();
            $table->string('nombre');
            $table->integer('cantidad');
            $table->string('seccion');
            $table->string('tipo');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_articulo');
    }
};
