<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;
    protected $table = 'niveles';  // ← agregar esta línea

    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'edad_minima',
        'edad_maxima',
        'valor_clase',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'valor_clase' => 'decimal:2',
        ];
    }
}
