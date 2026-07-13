<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\Area;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{

    public function index()
    {
        $cargos = Cargo::with('area')->get();
        $areas = Area::all();
        $empleados = Empleado::with('cargo.area')->get();

        return view('empleados.index', compact('cargos','areas','empleados'));
    }

    // ============================
    // 🔹 CREAR EMPLEADO
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'numero_empleado' => 'required|string|max:50|unique:empleados,numero_empleado',
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|unique:empleados,cedula',
            'cargo_id' => 'required|exists:cargos,id',
            'salario' => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // 🔥 GENERAR NÚMERO DE EMPLEADO AUTOMÁTICO
        $numeroEmpleado = 'EMP-' . str_pad(Empleado::count() + 1, 5, '0', STR_PAD_LEFT);

        // 📸 FOTO
        $rutaFoto = null;
        // FOTO
        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('empleados', 'public');
        } else {
            $rutaFoto = 'images/default-user.jpg'; // Ruta de la imagen por defecto
        }

        // 📅 LÓGICA DE FECHA FINAL
        $fechaFinal = null;

        if (in_array($request->estado, ['Despedido','Renuncia'])) {
            $fechaFinal = $request->fecha_finalizacion ?? now();
        }

        Empleado::create([
            'foto' => $rutaFoto,
            'numero_empleado' => $request->numero_empleado,
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'cargo_id' => $request->cargo_id,
            'inss' => $request->inss,
            'cuenta_bancaria' => $request->cuenta_bancaria,
            'salario' => $request->salario,
            'fecha_inicio' => $request->fecha_inicio,
            'estado' => $request->estado,
            'fecha_finalizacion' => null
        ]);

        return back()->with('success','Empleado creado correctamente');
    }


    // ============================
    // 🔹 ACTUALIZAR EMPLEADO
    // ============================
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|unique:empleados,cedula,' . $id,
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'cargo_id' => 'required|exists:cargos,id',
            'salario' => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:Activo,Despedido,Renuncia,Subsidio',
            'fecha_finalizacion' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // 📸 ACTUALIZAR FOTO (ELIMINANDO LA ANTERIOR)
        if ($request->hasFile('foto')) {

            if ($empleado->foto) {
                Storage::disk('public')->delete($empleado->foto);
            }

            $empleado->foto = $request->file('foto')->store('empleados','public');
        }

        // 📅 LÓGICA DE ESTADO
        $fechaFinal = null;

        if (in_array($request->estado, ['Despedido','Renuncia'])) {
            $fechaFinal = $request->fecha_finalizacion ?? now();
        }

        if ($request->estado === 'Activo') {
            $fechaFinal = null;
        }

        $empleado->update([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'inss' => $request->inss,
            'cuenta_bancaria' => $request->cuenta_bancaria,
            'cargo_id' => $request->cargo_id,
            'salario' => $request->salario,
            'fecha_inicio' => $request->fecha_inicio,
            'estado' => $request->estado,
            'fecha_finalizacion' => $fechaFinal
        ]);

        return back()->with('success','Empleado actualizado correctamente');
    }

}