<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Configuracion;
use App\Models\Nivel;
use App\Models\Grupo;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CarnetController extends Controller
{
    public function index()
    {
        // Solo alumnos que tienen al menos una matrícula activa
        // (así el carnet siempre tiene nivel y grupo para mostrar)
        $alumnos = Alumno::with(['matriculas' => function ($q) {
            $q->where('estado', 'activa')->with('grupo.nivel')->latest('fecha_matricula');
        }, 'acudiente'])
            ->whereHas('matriculas', function ($q) {
                $q->where('estado', 'activa');
            })
            ->orderBy('nombre_completo')
            ->get();

        $niveles = Nivel::where('estado', 'activo')->orderBy('orden')->get();
        $grupos  = Grupo::where('estado', 'activo')->orderBy('nombre')->get();

        return view('admin.carnets.index', compact('alumnos', 'niveles', 'grupos'));
    }

    public function generar($id)
    {
        $alumno = Alumno::with('acudiente')->findOrFail($id);

        $matricula = $alumno->matriculas()
            ->where('estado', 'activa')
            ->with('grupo.nivel', 'grupo.sede')
            ->latest('fecha_matricula')
            ->first();

        $configuracion = Configuracion::first();
        $anioLectivo = now()->year;
        $qrBase64 = $this->generarQrBase64($alumno);

        $pdf = Pdf::loadView('admin.carnets.pdf', compact(
            'alumno',
            'matricula',
            'configuracion',
            'qrBase64',
            'anioLectivo'
        ))->setPaper([0, 0, 155.91, 240.95], 'portrait');

        return $pdf->stream('carnet-' . ($alumno->codigo ?? $alumno->id) . '.pdf');
    }

    /**
     * Genera un PDF con un carnet por página. Se puede filtrar por
     * nivel o por grupo; si no se envía ningún filtro, incluye a
     * todos los alumnos con matrícula activa.
     */
    public function imprimirMasivo(\Illuminate\Http\Request $request)
    {
        $nivelId = $request->get('nivel_id');
        $grupoId = $request->get('grupo_id');

        $alumnos = Alumno::with(['acudiente', 'matriculas' => function ($q) {
                $q->where('estado', 'activa')->with('grupo.nivel')->latest('fecha_matricula');
            }])
            ->whereHas('matriculas', function ($q) use ($nivelId, $grupoId) {
                $q->where('estado', 'activa');

                if ($grupoId) {
                    $q->where('grupo_id', $grupoId);
                } elseif ($nivelId) {
                    $q->whereHas('grupo', function ($gq) use ($nivelId) {
                        $gq->where('nivel_id', $nivelId);
                    });
                }
            })
            ->orderBy('nombre_completo')
            ->get();

        if ($alumnos->isEmpty()) {
            return redirect()
                ->route('admin.carnets.index')
                ->with('error', 'No hay alumnos con matrícula activa para los filtros seleccionados.');
        }

        $configuracion = Configuracion::first();
        $anioLectivo = now()->year;

        $registros = $alumnos->map(function ($alumno) {
            return [
                'alumno'    => $alumno,
                'matricula' => $alumno->matriculas->first(),
                'qrBase64'  => $this->generarQrBase64($alumno),
            ];
        });

        // Título descriptivo según el filtro aplicado
        $titulo = 'Todos los alumnos';
        if ($grupoId) {
            $grupo = Grupo::with('nivel')->find($grupoId);
            $titulo = 'Grupo: ' . ($grupo->nombre ?? '');
        } elseif ($nivelId) {
            $nivel = Nivel::find($nivelId);
            $titulo = 'Nivel: ' . ($nivel->nombre ?? '');
        }

        $pdf = Pdf::loadView('admin.carnets.pdf-masivo', compact(
            'registros', 'configuracion', 'anioLectivo', 'titulo'
        ))->setPaper([0, 0, 155.91, 240.95], 'portrait');

        return $pdf->stream('carnets-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Genera el código QR en base64 (PNG vía GD, sin requerir Imagick)
     * para un alumno. El QR guarda su código — dato que luego se
     * escaneará para registrar asistencia.
     */
    private function generarQrBase64(Alumno $alumno): string
    {
        $valorQr = $alumno->codigo ?: 'ALU-' . $alumno->id;

        $qrCode = new QrCode(data: $valorQr, size: 200, margin: 5);
        $writer = new PngWriter();
        $resultado = $writer->write($qrCode);

        return base64_encode($resultado->getString());
    }
}
