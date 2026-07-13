<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NominaDetalleDeduccion extends Model
{
    use HasFactory;


    protected $table = 'nomina_detalle_deducciones';


    protected $fillable = [

        'nomina_detalle_id',

        'deduccion_id',

        'nombre',

        'tipo',

        'valor',

        'monto_aplicado',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */


    // Pertenece al detalle de nómina del empleado
    public function nominaDetalle()
    {
        return $this->belongsTo(
            NominaDetalle::class,
            'nomina_detalle_id'
        );
    }



    // Referencia a la deducción configurada
    public function deduccion()
    {
        return $this->belongsTo(
            Deduccion::class,
            'deduccion_id'
        );
    }

}