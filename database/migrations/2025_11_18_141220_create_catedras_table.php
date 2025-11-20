<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catedras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->cascadeOnDelete();
            $table->string('codigo', 20);
            $table->string('codigo_grupo', 50)->nullable();
            $table->string('codigo_canal', 50)->nullable();
            $table->enum('modalidad', ['presencial', 'distancia', 'ambas'])->default('ambas');
            $table->timestamps();

            $table->unique(['materia_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catedras');
    }
};
