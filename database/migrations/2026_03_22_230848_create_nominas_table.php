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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->nullable(); // Ej: NOM-2026-03-01
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->decimal('total_devengado', 12, 2)->default(0);
            $table->decimal('total_deducciones', 12, 2)->default(0);
            $table->decimal('total_neto', 12, 2)->default(0);
            $table->decimal('total_empresa', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
