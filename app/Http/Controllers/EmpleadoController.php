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
            'nombre' => 'required|string|max:255',
            'numero_empleado' => 'required|string|max:50',
            'cedula' => 'required|string|max:25',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'area_id' => 'required|exists:areas,id',
            'cargo_id' => 'required|exists:cargos,id',
            'inss' => 'required|string|max:50',
            'cuenta_bancaria' => 'required|string|max:100',
            'salario' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:Activo,Subsidio,Renuncia,Despedido',
            'fecha_finalizacion' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            // Nombre
            'nombre.required' => 'Debe ingresar el nombre del empleado.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            // Número de empleado
            'numero_empleado.required' => 'Debe ingresar el número de empleado.',
            'numero_empleado.max' => 'El número de empleado es demasiado largo.',

            // Cédula
            'cedula.required' => 'Debe ingresar la cédula.',
            'cedula.max' => 'La cédula es demasiado larga.',

            // Correo
            'correo.required' => 'Debe ingresar el correo electrónico.',
            'correo.email' => 'Debe ingresar un correo electrónico válido.',
            'correo.max' => 'El correo electrónico es demasiado largo.',

            // Teléfono
            'telefono.required' => 'Debe ingresar el teléfono.',
            'telefono.max' => 'El teléfono es demasiado largo.',

            // Área
            'area_id.required' => 'Debe seleccionar un área.',
            'area_id.exists' => 'El área seleccionada no es válida.',

            // Cargo
            'cargo_id.required' => 'Debe seleccionar un cargo.',
            'cargo_id.exists' => 'El cargo seleccionado no es válido.',

            // INSS
            'inss.required' => 'Debe ingresar el número de INSS.',
            'inss.max' => 'El número de INSS es demasiado largo.',

            // Cuenta bancaria
            'cuenta_bancaria.required' => 'Debe ingresar la cuenta bancaria.',
            'cuenta_bancaria.max' => 'La cuenta bancaria es demasiado larga.',

            // Salario
            'salario.required' => 'Debe ingresar el salario.',
            'salario.numeric' => 'El salario debe ser un valor numérico.',
            'salario.min' => 'El salario no puede ser menor que cero.',

            // Fecha inicio
            'fecha_inicio.required' => 'Debe seleccionar la fecha de inicio.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',

            // Estado
            'estado.required' => 'Debe seleccionar el estado.',
            'estado.in' => 'El estado seleccionado no es válido.',

            // Fecha finalización
            'fecha_finalizacion.date' => 'La fecha de finalización no es válida.',

            // Foto
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser JPG, JPEG o PNG.',
            'foto.max' => 'La foto no puede superar los 2 MB.',
        ]);

        // 📸 FOTO
        $rutaFoto = null;

        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('empleados', 'public');
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

        return back()->with('success', 'Empleado creado correctamente.');
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