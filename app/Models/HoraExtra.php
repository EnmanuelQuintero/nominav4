<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'horas_extras';

    protected $fillable = [
        'empleado_dia_id',
        'cantidad_horas',
        'pagada'
    ];

    // 🔹 RELACIÓN: pertenece al día trabajado
    public function empleadoDia()
    {
        return $this->belongsTo(EmpleadoDia::class);
    }
    public function dia()
    {
        return $this->belongsTo(EmpleadoDia::class, 'empleado_dia_id');
    }
}