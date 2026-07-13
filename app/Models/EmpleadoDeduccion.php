<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoDeduccion extends Model
{
    protected $table = 'empleado_deduccion';

    protected $fillable = [
        'empleado_id',
        'deduccion_id',
        'activa',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];


    /**
     * Empleado al que pertenece la deducción
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }


    /**
     * Tipo de deducción aplicada
     */
    public function deduccion()
    {
        return $this->belongsTo(Deduccion::class);
    }
}