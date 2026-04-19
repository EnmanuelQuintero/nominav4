<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpleadoDia;
use App\Models\HoraExtra;

class EmpleadoDiaController extends Controller
{

    // 🔹 GUARDAR / ACTUALIZAR DIA
public function store(Request $request)
{
    $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'tipo' => 'required',
        'fecha' => 'nullable|date',
        'fechas' => 'nullable|array'
    ]);

    // 🔥 Normalizamos → siempre trabajar como array
    $fechas = [];

    if ($request->fecha) {
        $fechas[] = $request->fecha;
    }

    if ($request->fechas) {
        $fechas = array_merge($fechas, $request->fechas);
    }

    if (empty($fechas)) {
        return response()->json([
            'success' => false,
            'mensaje' => 'Debe seleccionar al menos un día'
        ], 422);
    }

    foreach ($fechas as $fecha) {

        // 🔹 Crear o actualizar día
        $dia = EmpleadoDia::updateOrCreate(
            [
                'empleado_id' => $request->empleado_id,
                'fecha' => $fecha
            ],
            [
                'tipo' => $request->tipo
            ]
        );

        // 🔥 HORAS EXTRAS
        if ($request->tipo === 'trabajado') {

            $horas = $request->horas ?? 0;

            if ($horas > 0) {

                if ($dia->horasExtras) {
                    $dia->horasExtras->update([
                        'cantidad_horas' => $horas
                    ]);
                } else {
                    HoraExtra::create([
                        'empleado_dia_id' => $dia->id,
                        'cantidad_horas' => $horas,
                        'pagada' => false
                    ]);
                }

            } else {
                // 🔥 si manda 0 → eliminar
                $dia->horasExtras()->delete();
            }

        } else {
            // 🔥 cualquier otro tipo elimina horas extras
            $dia->horasExtras()->delete();
        }
    }

    return response()->json([
        'success' => true,
        'mensaje' => 'Días guardados correctamente'
    ]);
}
    // 🔹 OBTENER DATOS DEL CALENDARIO
    public function obtenerPorEmpleado($empleado_id)
    {
        $dias = EmpleadoDia::with('horasExtras')
            ->where('empleado_id', $empleado_id)
            ->get();

        return response()->json($dias);
    }

}