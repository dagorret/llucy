<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('materias', 'carrera_id')) {
            Schema::table('materias', function (Blueprint $table) {
                $table->dropForeign(['carrera_id']);
                $table->dropColumn('carrera_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            if (! Schema::hasColumn('materias', 'carrera_id')) {
                $table->foreignId('carrera_id')->after('plan_id')->nullable()->constrained('carreras')->cascadeOnDelete();
            }
        });
    }
};
