<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{

    public function index()
    {
        $areas = Area::all();
        return view('areas.index', compact('areas'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        Area::create([
            'nombre' => $request->nombre
        ]);

        return redirect()->back()->with('success','Área creada correctamente');
    }

}