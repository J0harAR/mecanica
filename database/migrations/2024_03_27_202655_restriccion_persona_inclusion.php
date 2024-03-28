<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         DB::unprepared('
            CREATE TRIGGER prevent_multiple_roles_alumno BEFORE INSERT ON alumno
            FOR EACH ROW
            BEGIN
                IF EXISTS (SELECT 1 FROM docente WHERE curp = NEW.curp) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Una persona no puede ser docente y alumno al mismo tiempo";
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER prevent_multiple_roles_docente BEFORE INSERT ON docente
            FOR EACH ROW
            BEGIN
                IF EXISTS (SELECT 1 FROM alumno WHERE curp = NEW.curp) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Una persona no puede ser docente y alumno al mismo tiempo";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('docente', function (Blueprint $table) {
            $table->dropForeign(['curp']);
        });

        Schema::table('alumno', function (Blueprint $table) {
            $table->dropForeign(['curp']);
        });

    }
};
