<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'valor',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
        ];
    }

    /**
     * Calcula el valor del descuento sobre un monto base.
     */
    public function calcularDescuento(float $montoBase): float
    {
        if ($this->tipo === 'porcentaje') {
            return round($montoBase * ($this->valor / 100), 2);
        }

        return (float) $this->valor;
    }
}
