<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (! Schema::hasColumn('alumnos', 'teams_password')) {
                $table->string('teams_password')->nullable()->after('estado_ingreso');
            }

            if (! Schema::hasColumn('alumnos', 'teams_payload')) {
                $table->longText('teams_payload')->nullable()->after('teams_password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (Schema::hasColumn('alumnos', 'teams_payload')) {
                $table->dropColumn('teams_payload');
            }
            if (Schema::hasColumn('alumnos', 'teams_password')) {
                $table->dropColumn('teams_password');
            }
        });
    }
};
