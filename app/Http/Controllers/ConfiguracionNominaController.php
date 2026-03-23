<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParametroNomina;
use App\Models\TablaIR;

class ConfiguracionNominaController extends Controller
{

    public function index()
    {
        $parametros = ParametroNomina::latest()->first();
        $tablaIR = TablaIR::orderBy('desde')->get();

        return view('configuracion.index', compact('parametros','tablaIR'));
    }

    // 🔵 GUARDAR PORCENTAJES
    public function guardarParametros(Request $request)
    {
        $request->validate([
            'porcentaje_inss_laboral' => 'required|numeric',
            'porcentaje_inss_patronal' => 'required|numeric',
            'porcentaje_inatec' => 'required|numeric',
        ]);

        ParametroNomina::create($request->all());

        return back()->with('success','Parámetros actualizados');
    }

    // 🟡 GUARDAR TRAMO IR
public function guardarIR(Request $request)
{
    $request->validate([
        'desde' => 'nullable|numeric|min:0',
        'hasta' => 'nullable|numeric|min:0',
        'porcentaje' => 'nullable|numeric|min:0',
        'base' => 'nullable|numeric|min:0',
    ]);

    TablaIR::create([
        'desde' => $request->desde ?? 0,
        'hasta' => $request->hasta !== null ? $request->hasta : null,
        'porcentaje' => $request->porcentaje ?? 0,
        'base' => $request->base ?? 0,
    ]);

    return back()->with('success','Tramo IR agregado');
}

    // 🔴 ELIMINAR TRAMO IR
    public function eliminarIR($id)
    {
        TablaIR::findOrFail($id)->delete();

        return back()->with('success','Tramo eliminado');
    }

}