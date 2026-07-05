<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acudiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'parentesco',
        'telefono',
        'correo',
        'direccion',
        'observaciones',
        'estado',
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
