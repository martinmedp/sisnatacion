<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumno_id',
        'grupo_id',
        'descuento_id',
        'fecha_matricula',
        'valor_total_nivel',
        'descuento_aplicado',
        'numero_cuotas',
        'periodicidad',
        'valor_cuota',
        'estado',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class);
    }

    public function getValorTotalConDescuentoAttribute(): float
    {
        return $this->valor_total_nivel - $this->descuento_aplicado;
    }

    public function getPeriodicidadFormateadaAttribute(): string
    {
        return $this->periodicidad === 'quincenal' ? 'Quincenal' : 'Mensual';
    }
}
