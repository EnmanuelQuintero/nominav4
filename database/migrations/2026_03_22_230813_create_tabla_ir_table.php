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
        Schema::create('tabla_ir', function (Blueprint $table) {
            $table->id();

            $table->decimal('desde', 10, 2);
            $table->decimal('hasta', 10, 2)->nullable();

            $table->decimal('porcentaje', 5, 2); // Ej: 15%
            $table->decimal('base', 10, 2)->default(0); // monto fijo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_ir');
    }
};
