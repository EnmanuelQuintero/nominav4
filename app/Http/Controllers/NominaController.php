<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use App\Models\Empleado;
use App\Models\NominaDetalle;
use App\Models\ParametroNomina;
use App\Models\HoraExtra;
use App\Models\EmpleadoDia;
use App\Models\TablaIR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NominaController extends Controller
{
    public function index()
    {
        $nominas = Nomina::with('detalles')->latest()->get();

        // 🔥 RESUMEN
        $totalNominas = $nominas->count();
        $totalPagado = $nominas->sum('total_neto');
        $totalEmpleados = Empleado::count();
        $totalHorasExtras = NominaDetalle::sum('horas_extra_cantidad');

        return view('nominas.index', compact(
            'nominas',
            'totalNominas',
            'totalPagado',
            'totalEmpleados',
            'totalHorasExtras'
        ));
    }





public function preview(Request $request)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    $fechaInicio = $request->fecha_inicio;
    $fechaFin = $request->fecha_fin;

    // 🔥 BUSCAR SI YA EXISTE UNA NÓMINA CON EL MISMO PERÍODO
    $nominaExistente = Nomina::where(
            'fecha_inicio', '<=', $fechaFin
        )
        ->where(
            'fecha_fin', '>=', $fechaInicio
        )
        ->first();
        
    // 🔥 EMPLEADOS ACTIVOS
    $empleados = Empleado::with('cargo.area')
        ->where('estado', 'Activo')
        ->get();

    // 🔥 PARAMETROS
    $parametros = ParametroNomina::latest()->first();
    $tablaIR = TablaIr::orderBy('desde')->get();

    $detalles = [];
    $resumen = [
        'devengado' => 0,
        'deducciones' => 0,
        'neto' => 0,
        'empresa' => 0,
    ];

    foreach ($empleados as $emp) {

        // 💰 SALARIOS
        $salarioMensual = $emp->salario;
        $salarioDiario = $salarioMensual / 30;
        

        // 📅 HORAS EXTRAS
        $horasExtra = HoraExtra::where('pagada', false)
            ->whereHas('dia', function ($q) use ($emp, $fechaInicio, $fechaFin) {
                $q->where('empleado_id', $emp->id)
                  ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->sum('cantidad_horas');

        $tiposPagados = ['trabajado', 'vacaciones', 'compensado'];

        $diasTrabajados = EmpleadoDia::where('empleado_id', $emp->id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->whereIn('tipo', $tiposPagados)
            ->count();

        $diasSubsidio = EmpleadoDia::where('empleado_id', $emp->id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('tipo', 'subsidio')
            ->count();
        $salarioQuincenal = $diasTrabajados * $salarioDiario;
        // 💵 INGRESOS
        $montoHorasExtra = (($salarioDiario / 8) * $horasExtra) * 2;
        $subsidio = $diasSubsidio * $salarioDiario;
        $feriado = 0;

        $devengado = $salarioQuincenal + $montoHorasExtra + $subsidio + $feriado;

        // INSS laboral
        $inss = $devengado * ($parametros->porcentaje_inss_laboral / 100);

        // Base IR anualizada
        $baseIRMensual = ($devengado - $inss) * 2;
        $baseIRMensual = $baseIRMensual * 12;

        $irMensual = 0;

        foreach ($tablaIR as $tramo) {

            if (
                $baseIRMensual >= $tramo->desde &&
                ($tramo->hasta === null || $baseIRMensual <= $tramo->hasta)
            ) {

                // todo en centavos
                $exceso = (int) round(
                    ($baseIRMensual - ($tramo->desde - 1)) * 100
                );

                $base = (int) round($tramo->base * 100);

                $irMensualCentavos =
                    (($exceso * $tramo->porcentaje) / 100)
                    + $base;

                // Mensual → mensual real
                $irMensualCentavos = (int) round($irMensualCentavos);

                // Mensual → quincenal
                $irCentavos = (int) round($irMensualCentavos / 12 / 2);

                break;
            }
        }

        // Convertir a córdobas solamente al final
        $ir = $irCentavos / 100;

        // Total deducción y neto
        $deduccion = $inss + $ir;
        $neto = $devengado - $deduccion;

        // 🏢 APORTES EMPRESA
        $inatec = $devengado * ($parametros->porcentaje_inatec / 100);
        $inssPatronal = $devengado * ($parametros->porcentaje_inss_patronal / 100);

        $detalles[] = [
            'id' => $emp->id,
            'area' => $emp->cargo->area->nombre,
            'numero' => $emp->numero_empleado,
            'nombre' => $emp->nombre,
            'cargo' => $emp->cargo->nombre,
            'inss' => $emp->inss,

            'salario_mensual' => $salarioMensual,
            'salario_diario' => $salarioDiario,
            'dias_trabajados' => $diasTrabajados,

            'salario_quincenal' => $salarioQuincenal,
            'horas_extra' => $horasExtra,
            'monto_horas' => $montoHorasExtra,
            'dias_subsidio' => $diasSubsidio,
            'subsidio' => $subsidio,
            'feriado' => $feriado,

            'devengado' => $devengado,

            'inss_deduccion' => $inss,
            'ir' => $ir,
            'deduccion' => $deduccion,

            'neto' => $neto,

            'inatec' => $inatec,
            'inss_patronal' => $inssPatronal,
        ];

        $resumen['devengado'] += $devengado;
        $resumen['deducciones'] += $deduccion;
        $resumen['neto'] += $neto;
        $resumen['empresa'] += ($inatec + $inssPatronal);
    }

    $detallesAgrupados = collect($detalles)->groupBy('area');

    return view('nominas.preview', compact(
        'detallesAgrupados',
        'resumen',
        'fechaInicio',
        'fechaFin',
        'nominaExistente'
    ));
}


    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'detalles' => 'required|json'
        ]);

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $detalles = json_decode($request->detalles, true);

        $totalDevengado = 0;
        $totalDeducciones = 0;
        $totalNeto = 0;
        $costoEmpresa = 0;

        // 🔥 CALCULAR TOTALES
        foreach ($detalles as $area => $empleados) {
            foreach ($empleados as $emp) {
                $totalDevengado += $emp['devengado'];
                $totalDeducciones += $emp['deduccion'];
                $totalNeto += $emp['neto'];
                $costoEmpresa += $emp['inatec'] + $emp['inss_patronal'];
            }
        }

        // 🔥 ACTUALIZAR NÓMINA EXISTENTE
        if ($request->actualizar == 1 && $request->filled('nomina_id')) {

            $nomina = Nomina::findOrFail($request->nomina_id);

            $nomina->update([
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'total_devengado' => $totalDevengado,
                'total_deducciones' => $totalDeducciones,
                'total_neto' => $totalNeto,
                'total_empresa' => $costoEmpresa,
            ]);

            // 🔥 ELIMINAR DETALLES ANTERIORES
            $nomina->detalles()->delete();

            $mensaje = 'Nómina actualizada correctamente';

        } else {

            // 🔥 GENERAR CÓDIGO
            $contador = Nomina::count() + 1;

            $codigo = 'NOM-' .
                now()->format('Y-m') .
                '-' .
                str_pad($contador, 3, '0', STR_PAD_LEFT);

            // 🔥 CREAR NUEVA NÓMINA
            $nomina = Nomina::create([
                'codigo' => $codigo,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'total_devengado' => $totalDevengado,
                'total_deducciones' => $totalDeducciones,
                'total_neto' => $totalNeto,
                'total_empresa' => $costoEmpresa,
                'estado' => 'Pendiente'
            ]);

            $mensaje = 'Nómina guardada correctamente';
        }

        Log::info('STORE NOMINA', [
            'time' => now(),
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);
        // 🔥 GUARDAR DETALLES
        foreach ($detalles as $area => $empleados) {

            foreach ($empleados as $emp) {

                NominaDetalle::create([
                'nomina_id' => $nomina->id,
                'empleado_id' => $emp['id'],
                'area' => $area,
                'numero_empleado' => $emp['numero'],
                'nombre' => $emp['nombre'],
                'cargo' => $emp['cargo'],
                'inss' => $emp['inss'] ?? 0,

                'salario_mensual' => $emp['salario_mensual'],
                'salario_diario' => $emp['salario_diario'],
                'salario_quincenal' => $emp['salario_quincenal'],

                'dias_trabajados' => $emp['dias_trabajados'],

                'horas_extra_cantidad' => $emp['horas_extra'],
                'horas_extra_monto' => $emp['monto_horas'],

                'dias_subsidio' => $emp['dias_subsidio'],
                'subsidio_monto' => $emp['subsidio'],

                'feriado' => $emp['feriado'],

                'total_devengado' => $emp['devengado'],

                'detalle_inss' => $emp['inss_deduccion'],
                'detalle_ir' => $emp['ir'],
                'total_deduccion' => $emp['deduccion'],

                'neto_pagar' => $emp['neto'],

                'detalle_inatec' => $emp['inatec'],
                'detalle_inss_patronal' => $emp['inss_patronal'],
            ]);
        }
    }

    return redirect()
        ->route('nominas.index')
        ->with('success', $mensaje);
}

    public function show($id)
    {
        $nomina = Nomina::with('detalles')->findOrFail($id);

        // 🔥 Agrupar por área (si no guardaste área, usamos cargo como fallback)
        $detallesAgrupados = $nomina->detalles->groupBy(function($item){
            return $item->area ?? 'Sin área';
        });

        return view('nominas.show', compact('nomina','detallesAgrupados'));
    }

    public function pagar($id)
    {
        $nomina = Nomina::findOrFail($id);

        // 🔥 evitar reprocesar
        if($nomina->estado === 'Pagada'){
            return back()->with('error', 'Esta nómina ya fue pagada');
        }

        $nomina->update([
            'estado' => 'Pagada'
        ]);

        return back()->with('success', 'Nómina marcada como pagada');
    }
}