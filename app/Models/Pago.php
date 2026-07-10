<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'cobro_id',
        'valor_pagado',
        'fecha_pago',
        'metodo_pago',
        'observaciones',
    ];

    public function cobro()
    {
        return $this->belongsTo(Cobro::class);
    }
}
