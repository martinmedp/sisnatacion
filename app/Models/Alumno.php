<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',
        'codigo',
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
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function acudiente()
    {
        return $this->belongsTo(Acudiente::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function getEdadAttribute(): ?int
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }

        return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
    }
}
