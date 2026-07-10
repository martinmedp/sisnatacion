<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'numero_cuota',
        'fecha_vencimiento',
        'valor',
        'estado',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getValorPagadoAttribute(): float
    {
        return (float) $this->pagos()->sum('valor_pagado');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return round($this->valor - $this->valor_pagado, 2);
    }

    /**
     * Recalcula y guarda el estado del cobro según sus pagos registrados.
     */
    public function actualizarEstado(): void
    {
        $totalPagado = $this->pagos()->sum('valor_pagado');

        if ($totalPagado >= $this->valor) {
            $this->estado = 'pagado';
        } elseif ($totalPagado > 0) {
            $this->estado = 'parcial';
        } elseif ($this->fecha_vencimiento < now()->toDateString()) {
            $this->estado = 'vencido';
        } else {
            $this->estado = 'pendiente';
        }

        $this->save();
    }
}
