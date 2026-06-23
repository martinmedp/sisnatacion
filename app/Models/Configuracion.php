<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono1',
        'divisa',
        'correo_electronico',
        'web',
        'logo',
        'mision',
        'vision',
        'historia',
    ];
}
