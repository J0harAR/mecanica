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
        Schema::create('prestamo', function (Blueprint $table) {
            $table->id();
            $table->string('id_docente')->nullable();
            $table->string('id_herramientas')->nullable();
            
            $table->foreign('id_docente')->references('rfc')->on('docente')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_herramientas')->references('id_herramientas')->on('herramientas')->onUpdate('cascade')->onDelete('SET NULL');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamo');
    }
};
