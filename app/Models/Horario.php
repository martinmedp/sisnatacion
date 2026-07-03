<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'grupo_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function getDiaSemanaFormateadoAttribute(): string
    {
        return ucfirst($this->dia_semana);
    }

    public function getHorariosFormateadoAttribute(): string
    {
        return date('g:i a', strtotime($this->hora_inicio))
            . ' — '
            . date('g:i a', strtotime($this->hora_fin));
    }
}
