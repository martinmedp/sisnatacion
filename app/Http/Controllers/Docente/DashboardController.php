<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;

class DashboardController extends Controller
{
    public function index()
    {
        $docente = Docente::where('user_id', auth()->id())->first();

        $grupos = collect();
        if ($docente) {
            $grupos = Grupo::with(['nivel', 'sede', 'horarios'])
                ->where('docente_id', $docente->id)
                ->where('estado', 'activo')
                ->orderBy('nombre')
                ->get();
        }

        return view('panel-docente.dashboard', compact('docente', 'grupos'));
    }
}
