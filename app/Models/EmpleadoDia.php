<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoDia extends Model
{
    protected $table = 'empleado_dias';

    protected $fillable = [
        'empleado_id',
        'fecha',
        'tipo'
    ];

    

    // 🔹 RELACIÓN: pertenece a empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // 🔹 RELACIÓN: tiene horas extras
    public function horasExtras()
    {
        return $this->hasOne(HoraExtra::class);
    }
}