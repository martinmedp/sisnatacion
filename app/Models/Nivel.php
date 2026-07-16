<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'edad_minima',
        'edad_maxima',
        'valor_clase',
        'duracion_meses',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'valor_clase' => 'decimal:2',
        ];
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function criterios()
    {
        return $this->hasMany(CriterioEvaluacion::class)->orderBy('orden');
    }
}
