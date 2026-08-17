<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use App\Models\Empleado;
use App\Models\NominaDetalle;
use App\Models\ParametroNomina;
use App\Models\HoraExtra;
use App\Models\EmpleadoDia;
use App\Models\TablaIR;
use App\Models\NominaDetalleDeduccion;
use App\Services\NominaCalculo;
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





public function preview(
    Request $request,
    NominaCalculo $calculator
) {
    $datos = $request->validate([
        'fecha_inicio' => ['required', 'date'],
        'fecha_fin' => [
            'required',
            'date',
            'after_or_equal:fecha_inicio',
        ],
    ]);

    $fechaInicio = $datos['fecha_inicio'];
    $fechaFin = $datos['fecha_fin'];

    $nominaExistente = Nomina::where(
        'fecha_inicio',
        '<=',
        $fechaFin
    )
        ->where('fecha_fin', '>=', $fechaInicio)
        ->first();

    $empleados = Empleado::with([
        'cargo.area',
        'deducciones',
    ])
        ->where('estado', 'Activo')
        ->get();

    $parametros = ParametroNomina::latest()->firstOrFail();
    $tablaIR = TablaIr::orderBy('desde')->get();

    $detalles = [];
    $resumen = [
        'devengado' => 0,
        'deducciones' => 0,
        'otras' => 0,
        'neto' => 0,
        'empresa' => 0,
    ];

    foreach ($empleados as $empleado) {
        $detalle = $calculator->calcularEmpleado(
            $empleado,
            $fechaInicio,
            $fechaFin,
            $parametros,
            $tablaIR
        );

        $detalles[] = $detalle;

        $resumen['devengado'] += $detalle['devengado'];
        $resumen['deducciones'] += $detalle['deduccion'];
        $resumen['otras'] += $detalle['otras_deducciones'];
        $resumen['neto'] += $detalle['neto'];

        $resumen['empresa'] +=
            $detalle['inatec']
            + $detalle['inss_patronal'];
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

    public function store(
        Request $request,
        NominaCalculo $calculator
    ) {
        $datosValidados = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio',
            ],
            'detalles' => ['required', 'json'],
            'actualizar' => ['nullable', 'boolean'],
            'nomina_id' => ['nullable', 'integer', 'exists:nominas,id'],
        ]);

        $fechaInicio = $datosValidados['fecha_inicio'];
        $fechaFin = $datosValidados['fecha_fin'];

        /*
        * El JSON se utiliza solamente para conocer qué empleados
        * estaban incluidos en la previsualización.
        *
        * Los montos enviados desde el navegador no se utilizarán.
        */
        $detallesPreview = json_decode(
            $datosValidados['detalles'],
            true
        );

        $empleadosIds = collect($detallesPreview)
            ->flatten(1)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();

        if ($empleadosIds->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'detalles' => 'No se encontraron empleados para guardar.',
                ]);
        }

        return DB::transaction(function () use (
            $request,
            $calculator,
            $fechaInicio,
            $fechaFin,
            $empleadosIds
        ) {
            $empleados = Empleado::with([
                'cargo.area',
                'deducciones',
            ])
                ->whereIn('id', $empleadosIds)
                ->where('estado', 'Activo')
                ->get();

            if ($empleados->count() !== $empleadosIds->count()) {
                abort(
                    422,
                    'Uno o más empleados ya no existen o están inactivos.'
                );
            }

            $parametros = ParametroNomina::latest()->firstOrFail();
            $tablaIR = TablaIr::orderBy('desde')->get();

            /*
            * Recalcular nuevamente toda la nómina usando
            * el mismo servicio utilizado en preview().
            */
            $detallesCalculados = $empleados->map(function ($empleado) use (
                $calculator,
                $fechaInicio,
                $fechaFin,
                $parametros,
                $tablaIR
            ) {
                return $calculator->calcularEmpleado(
                    $empleado,
                    $fechaInicio,
                    $fechaFin,
                    $parametros,
                    $tablaIR
                );
            });

            $totales = $this->calcularTotalesNomina(
                $detallesCalculados
            );

            if (
                $request->boolean('actualizar')
                && $request->filled('nomina_id')
            ) {
                $nomina = Nomina::lockForUpdate()
                    ->findOrFail($request->nomina_id);

                $nomina->update([
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'total_devengado' => $totales['devengado'],
                    'total_deducciones' => $totales['deducciones'],
                    'total_neto' => $totales['neto'],
                    'total_empresa' => $totales['empresa'],
                ]);

                /*
                * Al eliminar NominaDetalle, sus deducciones deberían
                * eliminarse mediante onDelete('cascade').
                */
                $nomina->detalles()->delete();

                $mensaje = 'Nómina actualizada correctamente';
            } else {
                $codigo = $this->generarCodigoNomina();

                $nomina = Nomina::create([
                    'codigo' => $codigo,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'total_devengado' => $totales['devengado'],
                    'total_deducciones' => $totales['deducciones'],
                    'total_neto' => $totales['neto'],
                    'total_empresa' => $totales['empresa'],
                    'estado' => 'Pendiente',
                ]);

                $mensaje = 'Nómina guardada correctamente';
            }

            foreach ($detallesCalculados as $detalle) {
                $this->guardarDetalleNomina(
                    $nomina,
                    $detalle
                );
            }

            Log::info('Nómina guardada', [
                'nomina_id' => $nomina->id,
                'codigo' => $nomina->codigo,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'empleados' => $detallesCalculados->count(),
                'actualizada' => $request->boolean('actualizar'),
            ]);

            return redirect()
                ->route('nominas.index')
                ->with('success', $mensaje);
        });
    }

    private function calcularTotalesNomina(
        \Illuminate\Support\Collection $detalles
    ): array {
        return [
            'devengado' => $detalles->sum('devengado'),
            'deducciones' => $detalles->sum('deduccion'),
            'neto' => $detalles->sum('neto'),

            'empresa' => $detalles->sum(function ($detalle) {
                return $detalle['inatec']
                    + $detalle['inss_patronal'];
            }),
        ];
    }

    private function generarCodigoNomina(): string
    {
        $prefijo = 'NOM-' . now()->format('Y-m') . '-';

        $ultimaNomina = Nomina::where(
            'codigo',
            'like',
            $prefijo . '%'
        )
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $secuencia = 1;

        if ($ultimaNomina) {
            $ultimaSecuencia = (int) substr(
                $ultimaNomina->codigo,
                -3
            );

            $secuencia = $ultimaSecuencia + 1;
        }

        return $prefijo
            . str_pad($secuencia, 3, '0', STR_PAD_LEFT);
    }

    private function guardarDetalleNomina(
        Nomina $nomina,
        array $detalle
    ): void {
        $nominaDetalle = NominaDetalle::create([
            'nomina_id' => $nomina->id,
            'empleado_id' => $detalle['id'],

            'area' => $detalle['area'],
            'numero_empleado' => $detalle['numero'],
            'nombre' => $detalle['nombre'],
            'cargo' => $detalle['cargo'],
            'inss' => $detalle['inss'] ?? 0,

            'salario_mensual' => $detalle['salario_mensual'],
            'salario_diario' => $detalle['salario_diario'],
            'salario_quincenal' => $detalle['salario_quincenal'],

            'dias_trabajados' => $detalle['dias_trabajados'],

            'horas_extra_cantidad' => $detalle['horas_extra'],
            'horas_extra_monto' => $detalle['monto_horas'],

            'dias_subsidio' => $detalle['dias_subsidio'],
            'subsidio_monto' => $detalle['subsidio'],

            'feriado' => $detalle['feriado'],

            'total_devengado' => $detalle['devengado'],

            'detalle_inss' => $detalle['inss_deduccion'],
            'detalle_ir' => $detalle['ir'],
            'otras_deducciones' => $detalle['otras_deducciones'] ?? 0,
            'total_deduccion' => $detalle['deduccion'],

            'neto_pagar' => $detalle['neto'],

            'detalle_inatec' => $detalle['inatec'],
            'detalle_inss_patronal' => $detalle['inss_patronal'],
        ]);

        foreach (
            $detalle['detalle_otras_deducciones'] ?? []
            as $deduccion
        ) {
            NominaDetalleDeduccion::create([
                'nomina_detalle_id' => $nominaDetalle->id,
                'deduccion_id' => $deduccion['id'],
                'nombre' => $deduccion['nombre'],
                'tipo' => $deduccion['tipo'] ?? 'monto',
                'valor' => $deduccion['valor'] ?? 0,
                'monto_aplicado' => $deduccion['monto'],
            ]);
        }
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