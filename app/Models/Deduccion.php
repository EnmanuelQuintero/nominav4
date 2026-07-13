<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduccion extends Model
{
    protected $table = 'deducciones';

    protected $fillable = [
        'nombre',
        'tipo',
        'valor',
        'activa',
        'descripcion',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function empleados()
    {
        return $this->belongsToMany(
            Empleado::class,
            'empleado_deduccion'
        )
        ->withPivot([
            'activa',
            'fecha_inicio',
            'fecha_fin'
        ])
        ->withTimestamps();
    }

    public function nominaDetalles()
    {
        return $this->hasMany(
            NominaDetalleDeduccion::class,
            'deduccion_id'
        );
    }
}