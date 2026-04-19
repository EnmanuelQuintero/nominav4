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



    public function create()
    {
        $empleados = Empleado::with('cargo.area')->where('estado', 'Activo')->get();

        $parametros = ParametroNomina::latest()->first();
        $tablaIR = TablaIr::orderBy('desde')->get();

        return view('nominas.create', compact(
            'empleados',
            'parametros',
            'tablaIR'
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

        // 🔥 EMPLEADOS ACTIVOS
        $empleados = Empleado::with('cargo.area')
            ->where('estado','Activo')
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

        foreach($empleados as $emp){

            // 💰 SALARIOS
            $salarioMensual = $emp->salario;
            $salarioDiario = $salarioMensual / 30;
            $salarioQuincenal = $salarioMensual / 2;



            // 📅 (luego esto vendrá de asistencia)
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

            $diasSubsidio = 0;

            // 💵 INGRESOS
            $montoHorasExtra = (($salarioDiario / 8) * $horasExtra) * 2;
            $subsidio = $diasSubsidio * $salarioDiario;
            $feriado = 0;

            $devengado = $salarioQuincenal + $montoHorasExtra + $subsidio + $feriado;

            // INSS laboral
            $inss = $devengado * ($parametros->porcentaje_inss_laboral / 100);

            // Base IR quincenal -> proyectamos mensual
            $baseIRMensual = ($devengado - $inss) * 2;
            $baseIRMensual = $baseIRMensual * 12;
            //dd($baseIRMensual);
            // IR
            $irMensual = 0;
            foreach($tablaIR as $tramo){
                if($baseIRMensual >= $tramo->desde && ($tramo->hasta === null || $baseIRMensual <= $tramo->hasta)){

                    $exceso = round($baseIRMensual - ($tramo->desde - 1), 2);

                    $irMensual = round(
                        ($exceso * ($tramo->porcentaje / 100)) + $tramo->base,
                        2
                    );

                    $irMensual = round($irMensual / 12, 2);

                    break;
                }
            }

            // Ajustamos a quincena
            $ir = $irMensual / 2;

            // Total deducción y neto
            $deduccion = $inss + $ir;
            $neto = $devengado - $deduccion;

            // 🏢 EMPRESA
            $inatec = $devengado * ($parametros->porcentaje_inatec / 100);
            $inssPatronal = $devengado * ($parametros->porcentaje_inss_patronal / 100);

            // 🔥 GUARDAMOS EN ARRAY (NO BD)
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

            // 🔥 RESUMEN
            $resumen['devengado'] += $devengado;
            $resumen['deducciones'] += $deduccion;
            $resumen['neto'] += $neto;
            $resumen['empresa'] += ($inatec + $inssPatronal);
        }

        // 🔥 AGRUPAR POR AREA
        $detallesAgrupados = collect($detalles)->groupBy('area');

        return view('nominas.preview', compact(
            'detallesAgrupados',
            'resumen',
            'fechaInicio',
            'fechaFin'
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
    $detalles = json_decode($request->detalles, true); // array de detalles por área

    $totalDevengado = 0;
    $totalDeducciones = 0;
    $totalNeto = 0;
    $costoEmpresa = 0;

    // 🔥 Calculamos los totales directamente desde los detalles del preview
    foreach ($detalles as $area => $empleados) {
        foreach ($empleados as $emp) {
            $totalDevengado += $emp['devengado'];
            $totalDeducciones += $emp['deduccion'];
            $totalNeto += $emp['neto'];
            $costoEmpresa += $emp['inatec'] + $emp['inss_patronal'];
        }
    }

    // 🔥 Creamos la nómina ya con los totales
    $nomina = Nomina::create([
        'fecha_inicio' => $fechaInicio,
        'fecha_fin' => $fechaFin,
        'total_devengado' => $totalDevengado,
        'total_deducciones' => $totalDeducciones,
        'total_neto' => $totalNeto,
        'total_empresa' => $costoEmpresa
    ]);

    // 🔥 Guardamos cada detalle directamente desde los datos del preview
    foreach ($detalles as $area => $empleados) {
        foreach ($empleados as $emp) {
            NominaDetalle::create([
                'nomina_id' => $nomina->id,
                'empleado_id' => $emp['id'], // <-- ahora sí tenemos el ID real
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

    return redirect()->route('nominas.index')
                     ->with('success','Nómina guardada correctamente');
}


public function show($id)
{
    $nomina = Nomina::with('detalles')->findOrFail($id);

    // 🔥 Agrupar por área (si no guardaste área, usamos cargo como fallback)
    $detallesAgrupados = $nomina->detalles->groupBy(function($item){
        return $item->cargo ?? 'Sin área';
    });

    return view('nominas.show', compact('nomina','detallesAgrupados'));
}

}