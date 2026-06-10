<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'orden',
        'estado',
    ];
}
