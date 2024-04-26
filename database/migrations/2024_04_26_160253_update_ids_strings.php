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
        Schema::table('insumo_mantenimiento', function (Blueprint $table) {
            $table->string('id_insumo', 191)->change();
            $table->string('id_mantenimiento', 191)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumo_mantenimiento', function (Blueprint $table) {
            $table->string('id_insumo')->change();
            $table->string('id_mantenimiento')->change();
        });
    }
};
