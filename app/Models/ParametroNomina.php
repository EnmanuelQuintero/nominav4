<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroNomina extends Model
{
    protected $table = 'parametros_nomina';

    protected $fillable = [
        'porcentaje_inss_laboral',
        'porcentaje_inss_patronal',
        'porcentaje_inatec'
    ];
}