<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nomina_detalle_deducciones', function (Blueprint $table) {

            $table->id();

            $table->foreignId('nomina_detalle_id')
                ->constrained('nomina_detalles')
                ->cascadeOnDelete();


            // referencia a la deducción configurada
            $table->foreignId('deduccion_id')
                ->constrained('deducciones')
                ->cascadeOnDelete();


            // snapshot de la deducción
            $table->string('nombre');

            $table->enum('tipo', [
                'monto',
                'porcentaje'
            ]);


            $table->decimal('valor',10,2);

            // monto aplicado en esa nómina
            $table->decimal('monto_aplicado',10,2);


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_detalle_deducciones');
    }
};
