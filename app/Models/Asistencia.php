<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'matricula_id',
        'docente_id',
        'fecha',
        'estado',
        'observaciones',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
