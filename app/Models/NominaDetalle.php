<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominaDetalle extends Model
{
    protected $table = 'nomina_detalles';

    protected $fillable = [
        'nomina_id',
        'empleado_id',

        'numero_empleado',
        'nombre',
        'cargo',
        'inss',

        'salario_mensual',
        'salario_diario',
        'salario_quincenal',

        'dias_trabajados',

        'horas_extra_cantidad',
        'horas_extra_monto',

        'dias_subsidio',
        'subsidio_monto',

        'feriado',

        'total_devengado',

        'detalle_inss',
        'detalle_ir',
        'total_deduccion',

        'neto_pagar',

        'detalle_inatec',
        'detalle_inss_patronal'
    ];

    // 🔥 RELACIÓN: pertenece a nómina
    public function nomina()
    {
        return $this->belongsTo(Nomina::class);
    }

    // 🔥 RELACIÓN: pertenece a empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}