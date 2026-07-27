<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Grupo;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::with('grupo.nivel', 'grupo.sede', 'grupo.docente')
            ->orderByRaw("FIELD(dia_semana,'lunes','martes','miercoles','jueves','viernes','sabado','domingo')")
            ->orderBy('hora_inicio')
            ->get();

        return view('admin.horarios.index', compact('horarios'));
    }

    public function create(Request $request)
    {
        $grupos = Grupo::with(['nivel', 'sede'])
            ->where('estado', 'activo')
            ->when($request->sede_id, fn ($q) => $q->where('sede_id', $request->sede_id))
            ->orderBy('nombre')
            ->get();

        return view('admin.horarios.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'grupo_id'    => 'required|exists:grupos,id',
                'dia_semana'  => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
                'estado'      => 'required|in:activo,inactivo',
            ],
            [
                'grupo_id.required'    => 'Debes seleccionar un grupo.',
                'dia_semana.required'  => 'Debes seleccionar un día.',
                'hora_inicio.required' => 'La hora de inicio es obligatoria.',
                'hora_fin.required'    => 'La hora de fin es obligatoria.',
                'hora_fin.after'       => 'La hora de fin debe ser mayor a la hora de inicio.',
            ]
        );

        $horario = Horario::create($request->only([
            'grupo_id',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            'estado',
        ]));

        // Advertencia (no bloqueante): revisar si el docente de este grupo
        // ya tiene otra clase asignada en ese mismo horario, en cualquier sede.
        $grupo = Grupo::with('docente')->find($request->grupo_id);
        $mensaje = 'Horario creado correctamente';

        if ($grupo && $grupo->docente_id) {
            $choque = Horario::with('grupo')
                ->where('id', '!=', $horario->id)
                ->where('dia_semana', $request->dia_semana)
                ->where('estado', 'activo')
                ->where('hora_inicio', '<', $request->hora_fin)
                ->where('hora_fin', '>', $request->hora_inicio)
                ->whereHas('grupo', fn ($q) => $q->where('docente_id', $grupo->docente_id))
                ->first();

            if ($choque) {
                $mensaje .= '. ⚠️ Atención: el docente ' . $grupo->docente->nombre_completo
                    . ' ya tiene asignada otra clase en ese mismo horario (grupo "' . ($choque->grupo->nombre ?? '—') . '"). Verifica que no se cruce.';
            }
        }

        return redirect()
            ->route('admin.horarios.index')
            ->with('success', $mensaje);
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $grupos  = Grupo::with(['nivel', 'sede'])
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.horarios.edit', compact('horario', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::findOrFail($id);

        $request->validate(
            [
                'grupo_id'    => 'required|exists:grupos,id',
                'dia_semana'  => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
                'estado'      => 'required|in:activo,inactivo',
            ],
            [
                'grupo_id.required'    => 'Debes seleccionar un grupo.',
                'dia_semana.required'  => 'Debes seleccionar un día.',
                'hora_inicio.required' => 'La hora de inicio es obligatoria.',
                'hora_fin.required'    => 'La hora de fin es obligatoria.',
                'hora_fin.after'       => 'La hora de fin debe ser mayor a la hora de inicio.',
            ]
        );

        $horario->update($request->only([
            'grupo_id',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            'estado',
        ]));

        return redirect()
            ->route('admin.horarios.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()
            ->route('admin.horarios.index')
            ->with('success', 'Horario eliminado correctamente');
    }
}
