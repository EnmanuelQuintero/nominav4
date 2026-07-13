<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaIR extends Model
{
    protected $table = 'tabla_ir';

    protected $fillable = [
        'desde',
        'hasta',
        'porcentaje',
        'base'
    ];
}