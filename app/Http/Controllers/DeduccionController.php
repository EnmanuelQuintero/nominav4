<?php

namespace App\Http\Controllers;

use App\Models\Deduccion;
use Illuminate\Http\Request;

class DeduccionController extends Controller
{

    public function index()
    {
        $deducciones = Deduccion::orderBy('nombre')
            ->get();

        return view('deducciones.index', compact('deducciones'));
    }


    public function create()
    {
        return view('deducciones.create');
    }


    public function store(Request $request)
    {

        $request->validate([

            'nombre' => 'required|string|max:255',

            'tipo' => 'required|in:monto,porcentaje',

            'valor' => 'required|numeric|min:0',

            'descripcion' => 'nullable|string',

        ],[

            'nombre.required'=>'Debe ingresar el nombre de la deducción.',

            'tipo.required'=>'Debe seleccionar el tipo.',

            'valor.required'=>'Debe ingresar el valor.',

        ]);


        Deduccion::create([

            'nombre'=>$request->nombre,

            'tipo'=>$request->tipo,

            'valor'=>$request->valor,

            'descripcion'=>$request->descripcion,

            'activa'=>true

        ]);


        return redirect()
            ->route('deducciones.index')
            ->with('success','Deducción creada correctamente.');
    }



    public function edit(Deduccion $deduccion)
    {
        return view('deducciones.edit',
            compact('deduccion')
        );
    }



    public function update(Request $request, Deduccion $deduccion)
    {

        $request->validate([

            'nombre'=>'required|string|max:255',

            'tipo'=>'required|in:monto,porcentaje',

            'valor'=>'required|numeric|min:0',

            'descripcion'=>'nullable|string',

        ]);


        $deduccion->update([

            'nombre'=>$request->nombre,

            'tipo'=>$request->tipo,

            'valor'=>$request->valor,

            'descripcion'=>$request->descripcion,

        ]);


        return redirect()
            ->route('deducciones.index')
            ->with('success','Deducción actualizada correctamente.');
    }



    public function destroy(Deduccion $deduccion)
    {

        $deduccion->delete();


        return redirect()
            ->route('deducciones.index')
            ->with('success','Deducción eliminada correctamente.');
    }
}