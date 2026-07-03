<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'nivel_id',
        'sede_id',
        'docente_id',
        'cupo_maximo',
        'descripcion',
        'estado',
    ];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }
}
