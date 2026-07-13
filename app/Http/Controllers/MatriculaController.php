<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Cobro;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\Descuento;
use App\Models\Acudiente;
use App\Services\CodigoAlumnoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['alumno', 'grupo.nivel', 'grupo.sede'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        return view('admin.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        $alumnos    = Alumno::where('estado', 'activo')->orderBy('nombre_completo')->get();
        $grupos     = Grupo::with(['nivel', 'sede'])->where('estado', 'activo')->orderBy('nombre')->get();
        $descuentos = Descuento::where('estado', 'activo')->orderBy('nombre')->get();
        $acudientes = Acudiente::where('estado', 'activo')->orderBy('nombre_completo')->get();

        return view('admin.matriculas.create', compact('alumnos', 'grupos', 'descuentos', 'acudientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'       => 'required|exists:alumnos,id',
            'grupo_id'        => 'required|exists:grupos,id',
            'descuento_id'    => 'nullable|exists:descuentos,id',
            'fecha_matricula' => 'required|date',
            'numero_cuotas'   => 'required|integer|min:1|max:60',
            'periodicidad'    => 'required|in:mensual,quincenal',
        ]);

        $grupo = Grupo::with('nivel')->findOrFail($request->grupo_id);
        $nivel = $grupo->nivel;

        if (!$nivel) {
            return back()
                ->withInput()
                ->with('error', 'El grupo seleccionado no tiene un nivel asociado.');
        }

        $valorTotal = (float) $nivel->valor_clase;
        $numeroCuotas = (int) $request->numero_cuotas;
        $periodicidad = $request->periodicidad;

        $descuentoAplicado = 0;
        if ($request->descuento_id) {
            $descuento = Descuento::find($request->descuento_id);
            if ($descuento) {
                $descuentoAplicado = $descuento->calcularDescuento($valorTotal);
            }
        }

        $valorConDescuento = $valorTotal - $descuentoAplicado;
        $valorCuota = round($valorConDescuento / $numeroCuotas, 2);

        DB::beginTransaction();

        try {
            $matricula = Matricula::create([
                'alumno_id'          => $request->alumno_id,
                'grupo_id'           => $request->grupo_id,
                'descuento_id'       => $request->descuento_id,
                'fecha_matricula'    => $request->fecha_matricula,
                'valor_total_nivel'  => $valorTotal,
                'descuento_aplicado' => $descuentoAplicado,
                'numero_cuotas'      => $numeroCuotas,
                'periodicidad'       => $periodicidad,
                'valor_cuota'        => $valorCuota,
                'estado'             => 'activa',
            ]);

            // Generar los cobros (cuotas) de una sola vez, según la periodicidad elegida
            $fechaBase = Carbon::parse($request->fecha_matricula);
            $sumaCuotas = 0;

            for ($i = 1; $i <= $numeroCuotas; $i++) {
                $valorEstaCuota = $valorCuota;

                // Ajustar la última cuota para que la suma total cuadre exacto
                if ($i === $numeroCuotas) {
                    $valorEstaCuota = round($valorConDescuento - $sumaCuotas, 2);
                }

                $fechaVencimiento = $periodicidad === 'quincenal'
                    ? $fechaBase->copy()->addDays(15 * ($i - 1))
                    : $fechaBase->copy()->addMonthsNoOverflow($i - 1);

                Cobro::create([
                    'matricula_id'      => $matricula->id,
                    'numero_cuota'      => $i,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'valor'             => $valorEstaCuota,
                    'estado'            => 'pendiente',
                ]);

                $sumaCuotas += $valorEstaCuota;
            }

            // Generar el código del alumno si aún no tiene
            $alumno = Alumno::find($request->alumno_id);
            if ($alumno && !$alumno->codigo) {
                $alumno->codigo = CodigoAlumnoService::generar();
                $alumno->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al generar la matrícula: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula creada correctamente con ' . $numeroCuotas . ' cuota(s) ' . $periodicidad . '(es) generada(s).');
    }

    public function edit($id)
    {
        $matricula = Matricula::with(['alumno', 'grupo.nivel', 'grupo.sede', 'cobros' => function ($q) {
            $q->orderBy('numero_cuota');
        }])->findOrFail($id);

        return view('admin.matriculas.edit', compact('matricula'));
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:activa,finalizada,cancelada',
        ]);

        $matricula->update([
            'estado' => $request->estado,
        ]);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula actualizada correctamente');
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->delete(); // los cobros se eliminan en cascada

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula eliminada correctamente');
    }
}
