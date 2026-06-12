<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'foto',
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'estado_civil',
        'telefono',
        'direccion',
        'correo_personal',
        'correo_institucional',
        'profesion',
        'nivel_academico',
        'cargo',
        'perfil',
        'fecha_ingreso',
        'orden',
        'estado',
        'contacto_emergencia',
        'telefono_emergencia',
        'observaciones',
    ];
}
