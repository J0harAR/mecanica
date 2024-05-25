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
        Schema::create('docente_grupo', function (Blueprint $table) {
            $table->string('id_docente')->nullable();
            $table->string('clave_grupo')->nullable();
        

            $table->foreign('id_docente')->references('rfc')->on('docente')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('clave_grupo')->references('clave')->on('grupo')->onUpdate('cascade')->onDelete('SET NULL');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente_grupo');
    }
};
