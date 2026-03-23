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
            'fecha' => 'required|date',
            'tipo' => 'required'
        ]);

        // Crear o actualizar día
        $dia = EmpleadoDia::updateOrCreate(
            [
                'empleado_id' => $request->empleado_id,
                'fecha' => $request->fecha
            ],
            [
                'tipo' => $request->tipo
            ]
        );

        //  MANEJO DE HORAS EXTRAS

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
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Día guardado correctamente'
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