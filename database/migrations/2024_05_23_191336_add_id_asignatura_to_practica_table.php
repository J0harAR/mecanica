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
            $table->string('id_asignatura')->nullable()->after('id_docente');
            $table->foreign('id_asignatura')->references('clave')->on('asignatura')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practica', function (Blueprint $table) {
            //
        });
    }
};
