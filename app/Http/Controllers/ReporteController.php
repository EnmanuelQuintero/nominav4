<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomina;
use App\Models\NominaDetalle;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        $nominas = Nomina::orderBy('fecha_inicio', 'desc')->get();

        return view('reportes.index', compact('nominas'));
    }
    // 🔹 Obtener empleados por área
    public function empleadosPorNomina($id)
    {
        $nomina = Nomina::with('detalles')->findOrFail($id);

        return response()->json(
            $nomina->detalles->groupBy('area')->map(function ($items) {
                return $items->map(function ($d) {
                    return [
                        'id' => $d->id,
                        'nombre' => $d->nombre,
                        'cargo' => $d->cargo,
                    ];
                });
            })
        );
    }



    public function generarSolvencias(Request $request)
    {
        $ids = $request->empleados;

        if (!$ids || count($ids) === 0) {
            return response()->json(['error' => 'Sin empleados'], 400);
        }

        $detalles = NominaDetalle::whereIn('id', $ids)->get();

        $pdf = Pdf::loadView('reportes.solvencias-pdf', compact('detalles'))
                ->setPaper('letter', 'portrait');

        return $pdf->download('solvencias.pdf');
    }
}
