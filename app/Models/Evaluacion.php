<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'matricula_id',
        'criterio_id',
        'docente_id',
        'estado_criterio',
        'fecha_evaluacion',
        'observaciones',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function criterio()
    {
        return $this->belongsTo(CriterioEvaluacion::class, 'criterio_id');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
