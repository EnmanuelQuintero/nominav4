<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_deduccion', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empleado_id')
                  ->constrained('empleados')
                  ->cascadeOnDelete();

            $table->foreignId('deduccion_id')
                  ->constrained('deducciones')
                  ->cascadeOnDelete();

            $table->boolean('activa')
                  ->default(true);

            $table->date('fecha_inicio')
                  ->nullable();

            $table->date('fecha_fin')
                  ->nullable();

            $table->timestamps();

            $table->unique([
                'empleado_id',
                'deduccion_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_deduccion');
    }
};