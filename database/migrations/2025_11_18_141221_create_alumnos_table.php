<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20);
            $table->string('dni', 30);
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email_personal');
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedSmallInteger('cohorte')->nullable();
            $table->string('localidad')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_institucional')->nullable();
            $table->enum('estado_actual', ['preinscripto', 'aspirante', 'ingresante', 'alumno'])->default('preinscripto');
            $table->date('fecha_ingreso')->nullable();
            $table->string('estado_ingreso')->nullable();
            $table->timestamps();

            $table->unique(['tipo_documento', 'dni']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
