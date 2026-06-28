<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('docente')) {
            return redirect()->route('docente.dashboard');
        }

        if ($user->hasRole('alumno')) {
            return redirect()->route('alumno.dashboard');
        }

        if ($user->hasRole('acudiente')) {
            return redirect()->route('acudiente.dashboard');
        }

        abort(403, 'Tu usuario no tiene un rol asignado. Contacta al administrador.');
    }
}
