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
        Schema::create('docente', function (Blueprint $table) {
            $table->string('rfc')->primary();
            $table->string('curp')->nullable();
            $table->string('area');
            $table->string('foto');
            $table->string('telefono');
            $table->timestamps();
            $table->foreign('curp')->references('curp')->on('persona')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
