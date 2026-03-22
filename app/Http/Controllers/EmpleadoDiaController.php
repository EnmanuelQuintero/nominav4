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

        // Si NO es trabajado → eliminar extras si existen
        if ($request->tipo !== 'trabajado') {
            $dia->horasExtras()->delete();
        }

        // Si es trabajado y tiene horas extras
        if ($request->tipo === 'trabajado' && $request->has('horas')) {

            // Si ya existe → actualizar
            if ($dia->horasExtras) {
                $dia->horasExtras->update([
                    'cantidad_horas' => $request->horas
                ]);
            } else {
                // Crear
                HoraExtra::create([
                    'empleado_dia_id' => $dia->id,
                    'cantidad_horas' => $request->horas,
                    'pagada' => false
                ]);
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