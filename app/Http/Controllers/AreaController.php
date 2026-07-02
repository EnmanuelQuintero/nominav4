<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{

    public function index()
    {
        $areas = Area::all();
        return view('areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        // Normalizar el nombre
        $request->merge([
            'nombre' => Str::title(Str::lower(trim($request->nombre)))
        ]);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('areas', 'nombre'),
            ]
        ], [
            'nombre.unique' => 'Ya existe un área con ese nombre.'
        ]);

        Area::create([
            'nombre' => $request->nombre
        ]);

        return redirect()->back()->with('success', 'Área creada correctamente');
    }

}