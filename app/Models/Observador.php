<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observador extends Model
{
    use HasFactory;

    protected $table = 'observador';

    protected $fillable = [
        'alumno_id',
        'docente_id',
        'tipo',
        'fecha',
        'descripcion',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
