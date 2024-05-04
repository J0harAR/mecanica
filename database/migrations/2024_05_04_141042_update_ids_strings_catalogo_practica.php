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
        Schema::table('catalogo_practica', function (Blueprint $table) {
            $table->string('practica_id', 191)->change();
            $table->string('articulo_id', 191)->change();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogo_practica', function (Blueprint $table) {
            $table->string('practica_id')->change();
            $table->string('articulo_id')->change();
           
        });
    }
};
