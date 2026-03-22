<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horas_extras', function (Blueprint $table) {
            $table->id();

            // Relación con día trabajado
            $table->foreignId('empleado_dia_id')
                  ->constrained('empleado_dias')
                  ->cascadeOnDelete();

            // Cantidad de horas
            $table->integer('cantidad_horas');

            // Si ya fue pagada en nómina
            $table->boolean('pagada')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horas_extras');
    }
};