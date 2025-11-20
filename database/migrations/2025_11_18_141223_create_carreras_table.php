<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes')->cascadeOnDelete();
            $table->string('codigo', 20);
            $table->string('nombre');
            $table->timestamps();

            $table->unique(['plan_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
