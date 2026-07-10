<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'telefono',
        'correo',
        'cargo_id',
        'sede_id',
        'fecha_ingreso',
        'contacto_emergencia',
        'telefono_emergencia',
        'observaciones',
        'estado',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}
