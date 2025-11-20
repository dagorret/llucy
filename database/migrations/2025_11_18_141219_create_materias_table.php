<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('codigo', 20);
            $table->string('codigo_uti')->nullable();
            $table->unsignedTinyInteger('cuatrimestre')->default(1);
            $table->timestamps();

            $table->unique(['plan_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
