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
        Schema::create('parametros_nomina', function (Blueprint $table) {
            $table->id();

            $table->decimal('porcentaje_inss_laboral', 5, 2);     // Ej: 7.00
            $table->decimal('porcentaje_inss_patronal', 5, 2);    // Ej: 22.50
            $table->decimal('porcentaje_inatec', 5, 2);           // Ej: 2.00

            // IR puede ser más complejo (ver abajo 👇)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_nomina');
    }
};
