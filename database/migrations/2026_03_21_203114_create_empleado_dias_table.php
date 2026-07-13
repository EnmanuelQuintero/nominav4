<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_dias', function (Blueprint $table) {
            $table->id();

            // Relación con empleado
            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

            // Fecha del registro
            $table->date('fecha');

            // Tipo de día
            $table->enum('tipo', [
                'trabajado',
                'vacaciones',
                'compensado',
                'subsidio',
                'no_trabajado'
            ]);

            // Para evitar duplicados (un día por empleado)
            $table->unique(['empleado_id', 'fecha']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_dias');
    }
};