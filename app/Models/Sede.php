<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'encargado_id',
        'descripcion',
        'estado',
    ];

    public function encargado()
    {
        return $this->belongsTo(Docente::class, 'encargado_id');
    }
}
