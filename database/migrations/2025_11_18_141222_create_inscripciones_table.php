<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('materia_id')->nullable()->constrained('materias')->cascadeOnDelete();
            $table->foreignId('catedra_id')->nullable()->constrained('catedras')->cascadeOnDelete();
            $table->dateTime('fecha_inscripcion')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'baja'])->default('confirmada');
            $table->timestamps();

            $table->unique(['alumno_id', 'materia_id', 'catedra_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
