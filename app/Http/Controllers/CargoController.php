<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Area;
use Illuminate\Http\Request;

class CargoController extends Controller
{

    public function index()
    {
        $cargos = Cargo::with('area')->get();
        $areas = Area::all();

        return view('cargos.index', compact('cargos','areas'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id'
        ]);

        Cargo::create([
            'nombre' => $request->nombre,
            'area_id' => $request->area_id
        ]);

        return redirect()->back()->with('success','Cargo creado correctamente');
    }

}