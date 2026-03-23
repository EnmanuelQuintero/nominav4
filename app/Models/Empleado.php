<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'foto',
        'numero_empleado', // 🔥 NUEVO
        'nombre',
        'cedula',
        'correo',
        'telefono',
        'cargo_id',
        'inss',
        'cuenta_bancaria',
        'salario',
        'fecha_inicio',
        'estado',
        'fecha_finalizacion'
    ];

    // 🔥 CASTS (MUY IMPORTANTE)
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_finalizacion' => 'date',
        'salario' => 'decimal:2'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function dias()
    {
        return $this->hasMany(EmpleadoDia::class);
    }
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    // Acceso directo al área (PRO 🔥)
    public function area()
    {
        return $this->hasOneThrough(
            Area::class,
            Cargo::class,
            'id',        // FK en cargos
            'id',        // FK en areas
            'cargo_id',  // FK en empleados
            'area_id'    // FK en cargos
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (BONITO PARA VISTAS)
    |--------------------------------------------------------------------------
    */

    // 🔥 Foto completa
    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://i.pravatar.cc/100';
    }

    // 🔥 Estado badge automático
    public function getEstadoBadgeAttribute()
    {
        return match ($this->estado) {
            'Activo' => 'success',
            'Despedido' => 'danger',
            'Renuncia' => 'warning',
            'Subsidio' => 'info',
            default => 'secondary'
        };
    }
}