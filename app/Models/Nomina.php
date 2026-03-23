<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $fillable = [
        'codigo',
        'fecha_inicio',
        'fecha_fin',
        'total_devengado',
        'total_deducciones',
        'total_neto',
        'total_empresa'
    ];

    // 🔥 RELACIÓN: una nómina tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(NominaDetalle::class);
    }
}