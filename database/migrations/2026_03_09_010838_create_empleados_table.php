<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {

            $table->id();

            // FOTO 
            $table->string('foto')->nullable();
            $table->string('numero_empleado')->nullable();
            
            // DATOS PERSONALES
            $table->string('nombre');
            $table->string('cedula')->unique();

            // CONTACTO 
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();

            // RELACION
            $table->foreignId('cargo_id')
                  ->constrained('cargos')
                  ->cascadeOnDelete();

            // DATOS ADICIONALES
            $table->string('inss')->nullable();
            $table->string('cuenta_bancaria')->nullable();

            // SALARIO
            $table->decimal('salario', 10, 2);

            // FECHAS
            $table->date('fecha_inicio');

            $table->enum('estado', ['Activo','Despedido','Renuncia','Subsidio'])
                  ->default('Activo');

            $table->date('fecha_finalizacion')->nullable();

            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};