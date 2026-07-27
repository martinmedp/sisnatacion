<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioAtencionSede extends Model
{
    use HasFactory;

    protected $table = 'horarios_atencion_sede';

    protected $fillable = [
        'sede_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}
