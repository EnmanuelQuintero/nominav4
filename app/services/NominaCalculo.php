<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\EmpleadoDia;
use App\Models\HoraExtra;
use Illuminate\Support\Collection;

class NominaCalculo
{
    public function calcularEmpleado(
        Empleado $empleado,
        string $fechaInicio,
        string $fechaFin,
        object $parametros,
        Collection $tablaIR
    ): array {
        $salarios = $this->calcularSalarios(
            $empleado,
            $fechaInicio,
            $fechaFin
        );

        $ingresos = $this->calcularIngresos($salarios);

        $inssLaboral = $this->calcularInssLaboral(
            $ingresos['devengado'],
            $parametros->porcentaje_inss_laboral
        );

        $ir = $this->calcularIr(
            $ingresos['devengado'],
            $inssLaboral,
            $tablaIR
        );

        $otrasDeducciones = $this->calcularOtrasDeducciones(
            $empleado,
            $ingresos['devengado']
        );

        $deducciones = $this->calcularTotalDeducciones(
            $inssLaboral,
            $ir,
            $otrasDeducciones['total']
        );

        $neto = $this->calcularNeto(
            $ingresos['devengado'],
            $deducciones
        );

        $aportesEmpresa = $this->calcularAportesEmpresa(
            $ingresos['devengado'],
            $parametros
        );

        return [
            'id' => $empleado->id,
            'area' => $empleado->cargo->area->nombre,
            'numero' => $empleado->numero_empleado,
            'nombre' => $empleado->nombre,
            'cargo' => $empleado->cargo->nombre,
            'inss' => $empleado->inss,

            'salario_mensual' => $salarios['salario_mensual'],
            'salario_diario' => $salarios['salario_diario'],
            'dias_trabajados' => $salarios['dias_trabajados'],
            'salario_quincenal' => $salarios['salario_periodo'],

            'horas_extra' => $salarios['horas_extra'],
            'monto_horas' => $ingresos['monto_horas_extra'],

            'dias_subsidio' => $salarios['dias_subsidio'],
            'subsidio' => $ingresos['subsidio'],
            'feriado' => $ingresos['feriado'],

            'devengado' => $ingresos['devengado'],

            'inss_deduccion' => $inssLaboral,
            'ir' => $ir,

            'otras_deducciones' => $otrasDeducciones['total'],
            'detalle_otras_deducciones' => $otrasDeducciones['detalle'],

            'deduccion' => $deducciones,
            'neto' => $neto,

            'inatec' => $aportesEmpresa['inatec'],
            'inss_patronal' => $aportesEmpresa['inss_patronal'],
        ];
    }

    private function calcularSalarios(
        Empleado $empleado,
        string $fechaInicio,
        string $fechaFin
    ): array {
        $salarioMensual = (float) $empleado->salario;
        $salarioDiario = $salarioMensual / 30;

        $horasExtra = HoraExtra::where('pagada', false)
            ->whereHas('dia', function ($query) use (
                $empleado,
                $fechaInicio,
                $fechaFin
            ) {
                $query->where('empleado_id', $empleado->id)
                    ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->sum('cantidad_horas');

        $dias = EmpleadoDia::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get(['tipo']);

        $tiposPagados = [
            'trabajado',
            'vacaciones',
            'compensado',
        ];

        $diasTrabajados = $dias
            ->whereIn('tipo', $tiposPagados)
            ->count();

        $diasSubsidio = $dias
            ->where('tipo', 'subsidio')
            ->count();

        return [
            'salario_mensual' => $salarioMensual,
            'salario_diario' => $salarioDiario,
            'dias_trabajados' => $diasTrabajados,
            'dias_subsidio' => $diasSubsidio,
            'horas_extra' => (float) $horasExtra,
            'salario_periodo' => $diasTrabajados * $salarioDiario,
        ];
    }

    private function calcularIngresos(array $salarios): array
    {
        $montoHorasExtra = (
            ($salarios['salario_diario'] / 8)
            * $salarios['horas_extra']
        ) * 2;

        $subsidio = $salarios['dias_subsidio']
            * $salarios['salario_diario'];

        $feriado = 0;

        $devengado = $salarios['salario_periodo']
            + $montoHorasExtra
            + $subsidio
            + $feriado;

        return [
            'monto_horas_extra' => $montoHorasExtra,
            'subsidio' => $subsidio,
            'feriado' => $feriado,
            'devengado' => $devengado,
        ];
    }

    private function calcularInssLaboral(
        float $devengado,
        float $porcentaje
    ): float {
        return $devengado * ($porcentaje / 100);
    }

    private function calcularIr(
    float $devengado,
    float $inssLaboral,
    Collection $tablaIR
): float {
    // Limitar los valores iniciales a dos decimales.
    $devengado = round($devengado, 2);
    $inssLaboral = round($inssLaboral, 2);

    // Base gravable quincenal.
    $baseIrQuincenal = round(
        $devengado - $inssLaboral,
        2
    );

    // Quincenal a mensual.
    $baseIrMensual = round(
        $baseIrQuincenal * 2,
        2
    );

    // Mensual a anual.
    $baseIrAnual = round(
        $baseIrMensual * 12,
        2
    );

    foreach ($tablaIR as $tramo) {
        $desde = round((float) $tramo->desde, 2);

        $hasta = $tramo->hasta !== null
            ? round((float) $tramo->hasta, 2)
            : null;

        $perteneceAlTramo =
            $baseIrAnual >= $desde
            && (
                $hasta === null
                || $baseIrAnual <= $hasta
            );

        if (!$perteneceAlTramo) {
            continue;
        }

        $exceso = round(
            $baseIrAnual - ($desde - 1),
            2
        );

        $impuestoSobreExceso = round(
            $exceso * ((float) $tramo->porcentaje / 100),
            2
        );

        $irAnual = round(
            (float) $tramo->base + $impuestoSobreExceso,
            2
        );

        $irMensual = round(
            $irAnual / 12,
            2
        );

        $irQuincenal = round(
            $irMensual / 2,
            2
        );

        return $irQuincenal;
    }

    return 0.00;
}

    private function calcularOtrasDeducciones(
        Empleado $empleado,
        float $devengado
    ): array {
        $detalle = [];
        $total = 0;

        foreach ($empleado->deducciones as $deduccion) {
            $monto = $deduccion->tipo === 'monto'
                ? (float) $deduccion->valor
                : $devengado * ($deduccion->valor / 100);

            $detalle[] = [
                'id' => $deduccion->id,
                'nombre' => $deduccion->nombre,
                'tipo' => $deduccion->tipo,
                'valor' => $deduccion->valor,
                'monto' => $monto,
            ];

            $total += $monto;
        }

        return [
            'detalle' => $detalle,
            'total' => $total,
        ];
    }

    private function calcularTotalDeducciones(
        float $inssLaboral,
        float $ir,
        float $otrasDeducciones
    ): float {
        return $inssLaboral + $ir + $otrasDeducciones;
    }

    private function calcularNeto(
        float $devengado,
        float $deducciones
    ): float {
        return $devengado - $deducciones;
    }

    private function calcularAportesEmpresa(
        float $devengado,
        object $parametros
    ): array {
        return [
            'inatec' => $devengado
                * ($parametros->porcentaje_inatec / 100),

            'inss_patronal' => $devengado
                * ($parametros->porcentaje_inss_patronal / 100),
        ];
    }

    
}