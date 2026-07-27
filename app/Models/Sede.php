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
        'duracion_clase_minutos',
        'estado',
    ];

    public function encargado()
    {
        return $this->belongsTo(Docente::class, 'encargado_id');
    }

    public function horariosAtencion()
    {
        return $this->hasMany(HorarioAtencionSede::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
