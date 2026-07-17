<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use App\Models\Administrativo;

class DashboardController extends Controller
{
    public function index()
    {
        $administrativo = Administrativo::with(['cargo', 'sede'])
            ->where('user_id', auth()->id())
            ->first();

        return view('panel-administrativo.dashboard', compact('administrativo'));
    }
}
