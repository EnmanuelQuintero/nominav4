<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CargoController extends Controller
{

    public function index()
    {
        $cargos = Cargo::with('area')->get();
        $areas = Area::all();

        return view('cargos.index', compact('cargos', 'areas'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre' => Str::ucfirst(Str::lower(trim($request->nombre)))
        ]);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cargos', 'nombre'),
            ],
            'area_id' => 'required|exists:areas,id'
        ], [
            'nombre.unique' => 'Ya existe un cargo con ese nombre.'
        ]);

        Cargo::create([
            'nombre' => $request->nombre,
            'area_id' => $request->area_id
        ]);

        return redirect()->back()->with('success', 'Cargo creado correctamente');
    }

}