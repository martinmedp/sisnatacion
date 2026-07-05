<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'correo',
        'acudiente_id',
        'contacto_emergencia',
        'telefono_emergencia',
        'observaciones',
        'estado',
    ];

    public function acudiente()
    {
        return $this->belongsTo(Acudiente::class);
    }

    public function getEdadAttribute(): ?int
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }

        return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
    }
}
