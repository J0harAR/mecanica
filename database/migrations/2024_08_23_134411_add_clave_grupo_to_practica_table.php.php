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
        Schema::table('practica', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('clave_grupo')->nullable()->after('id_docente');
            
            $table->foreign('clave_grupo')->references('id')->on('grupo')->onUpdate('cascade')->onDelete('cascade');
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
