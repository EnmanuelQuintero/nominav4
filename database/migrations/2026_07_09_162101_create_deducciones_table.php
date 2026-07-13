<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deducciones', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');

            // monto fijo o porcentaje
            $table->enum('tipo', [
                'monto',
                'porcentaje'
            ]);

            $table->decimal('valor', 12, 2);

            $table->boolean('activa')
                  ->default(true);

            $table->text('descripcion')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deducciones');
    }
};