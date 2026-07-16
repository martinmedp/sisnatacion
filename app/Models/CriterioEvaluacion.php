<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriterioEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'criterios_evaluacion';

    protected $fillable = [
        'nivel_id',
        'nombre',
        'descripcion',
        'orden',
        'estado',
    ];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'criterio_id');
    }
}
