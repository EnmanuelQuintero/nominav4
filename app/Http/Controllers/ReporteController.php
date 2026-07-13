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
        $request->validate([
            'nomina_id' => 'required',
            'tipo' => 'required'
        ]);

        $query = NominaDetalle::where(
            'nomina_id',
            $request->nomina_id
        );

        if(
            $request->tipo == 'area'
            && $request->filled('area')
        ) {

            $query->where(
                'area',
                $request->area
            );
        }

        if(
            $request->tipo == 'empleado'
            && $request->filled('empleados')
        ) {

            $query->whereIn(
                'empleado_id',
                $request->empleados
            );

        }

        $detalles = $query
        ->with('deducciones')
        ->get();

        if($detalles->isEmpty()) {

            return response()->json([
                'error' => 'No se encontraron registros'
            ], 404);

        }

        $pdf = Pdf::loadView(
            'reportes.solvencias-pdf',
            compact('detalles')
        )->setPaper(
            'letter',
            'portrait'
        );


        //dd($request->all());
return $pdf->stream('solvencias.pdf');


    }

        public function obtenerFiltrosNomina($nominaId)
    {
        $detalles = NominaDetalle::where(
            'nomina_id',
            $nominaId
        )->get();

        $areas = $detalles
            ->pluck('area')
            ->unique()
            ->sort()
            ->values();

        $empleados = $detalles
            ->map(function ($item) {

                return [
                    'empleado_id' => $item->empleado_id,
                    'nombre' => $item->nombre,
                    'cargo' => $item->cargo,
                    'numero' => $item->numero_empleado,
                ];

            })
            ->unique('empleado_id')
            ->values();

        return response()->json([
            'areas' => $areas,
            'empleados' => $empleados
        ]);
    }
}
