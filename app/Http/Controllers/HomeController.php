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
            $acudiente = \App\Models\Acudiente::where('user_id', $user->id)->first();

            if ($acudiente && $acudiente->estado !== 'activo') {
                auth()->logout();
                return redirect()
                    ->route('login')
                    ->with('error', 'Tu registro está pendiente de revisión por el administrador. Te avisaremos cuando esté activo.');
            }

            return redirect()->route('acudiente.dashboard');
        }

        if ($user->hasRole('administrativo')) {
            return redirect()->route('administrativo.dashboard');
        }

        abort(403, 'Tu usuario no tiene un rol asignado. Contacta al administrador.');
    }
}
