<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'password',
        'estado'
    ];

    protected $hidden = [
        'password'
    ];
}
