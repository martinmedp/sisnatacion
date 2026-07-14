<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Configuracion;
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

        return view('admin.carnets.index', compact('alumnos'));
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

        // El QR guarda el código del alumno (o su ID si aún no tiene código asignado)
        // — este dato es el que luego se escaneará para registrar asistencia.
        // Se genera con endroid/qr-code (usa GD, no requiere Imagick).
        $valorQr = $alumno->codigo ?: 'ALU-' . $alumno->id;

        $qrCode = new QrCode(data: $valorQr, size: 200, margin: 5);
        $writer = new PngWriter();
        $resultadoQr = $writer->write($qrCode);
        $qrBase64 = base64_encode($resultadoQr->getString());

        $anioLectivo = now()->year;

        // Tamaño del carnet: 5.5cm x 8.5cm (formato vertical) convertido a puntos
        $pdf = Pdf::loadView('admin.carnets.pdf', compact(
            'alumno',
            'matricula',
            'configuracion',
            'qrBase64',
            'valorQr',
            'anioLectivo'
        ))->setPaper([0, 0, 155.91, 240.95], 'portrait');

        return $pdf->stream('carnet-' . ($alumno->codigo ?? $alumno->id) . '.pdf');
    }
}
