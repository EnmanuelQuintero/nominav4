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
        Schema::create('nomina_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nomina_id')->constrained()->cascadeOnDelete();
            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

            // 📌 DATOS DEL EMPLEADO (snapshot)
            $table->string('numero_empleado')->nullable();
            $table->string('nombre');
            $table->string('cargo');
            $table->string('inss')->nullable();

            // 💰 SALARIOS
            $table->decimal('salario_mensual', 10, 2);
            $table->decimal('salario_diario', 10, 2);
            $table->decimal('salario_quincenal', 10, 2);

            // 📅 TIEMPO
            $table->integer('dias_trabajados')->default(0);

            // ⏱️ HORAS EXTRA
            $table->integer('horas_extra_cantidad')->default(0);
            $table->decimal('horas_extra_monto', 10, 2)->default(0);

            // 🏥 SUBSIDIOS
            $table->integer('dias_subsidio')->default(0);
            $table->decimal('subsidio_monto', 10, 2)->default(0);

            // 🎉 OTROS INGRESOS
            $table->decimal('feriado', 10, 2)->default(0);

            // 💵 TOTALES INGRESOS
            $table->decimal('total_devengado', 10, 2);

            // ❌ DEDUCCIONES
            $table->decimal('detalle_inss', 10, 2)->default(0);
            $table->decimal('detalle_ir', 10, 2)->default(0);
            $table->decimal('total_deduccion', 10, 2);

            // 💰 NETO
            $table->decimal('neto_pagar', 10, 2);

            // 🏢 APORTES EMPRESA
            $table->decimal('detalle_inatec', 10, 2)->default(0);
            $table->decimal('detalle_inss_patronal', 10, 2)->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_detalles');
    }
};
