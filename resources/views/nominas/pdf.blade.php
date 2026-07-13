<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: sans-serif;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
        }

        th {
            background: #eee;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        .grupo {
            background: #ddd;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h3>Nómina: {{ $nomina->codigo }}</h3>
<p>
    Periodo: {{ $nomina->fecha_inicio }} - {{ $nomina->fecha_fin }}
</p>

<table>

    {{-- 🔥 CABECERA --}}
    <thead>

        <tr>
            <th colspan="7">Empleado</th>
            <th colspan="7">Ingresos</th>
            <th colspan="3">Deducciones</th>
            <th colspan="1">Neto</th>
            <th colspan="2">Aportes</th>
        </tr>

        <tr>

            {{-- EMPLEADO --}}
            <th>#</th>
            <th>Nombre</th>
            <th>Cargo</th>
            <th>INSS</th>
            <th>Sal. Mensual</th>
            <th>Sal. Diario</th>
            <th>Días</th>

            {{-- INGRESOS --}}
            <th>Sal. Quin.</th>
            <th>Hrs</th>
            <th>Monto Hrs</th>
            <th>Subsidio</th>
            <th>Monto Sub.</th>
            <th>Feriado</th>
            <th>Total Dev.</th>

            {{-- DEDUCCIONES --}}
            <th>INSS</th>
            <th>IR</th>
            <th>Total</th>

            {{-- NETO --}}
            <th>Neto</th>

            {{-- APORTES --}}
            <th>INATEC</th>
            <th>INSS Pat.</th>

        </tr>

    </thead>

    <tbody>

        @foreach($detallesAgrupados as $grupo => $empleados)

            {{-- 🔵 GRUPO --}}
            <tr class="grupo">
                <td colspan="20">
                    🏢 {{ $grupo }}
                </td>
            </tr>

            @foreach($empleados as $emp)
            <tr>

                {{-- EMPLEADO --}}
                <td>{{ $emp->numero_empleado }}</td>
                <td>{{ $emp->nombre }}</td>
                <td>{{ $emp->cargo }}</td>
                <td>{{ $emp->inss }}</td>

                <td class="text-right">{{ number_format($emp->salario_mensual,2) }}</td>
                <td class="text-right">{{ number_format($emp->salario_diario,2) }}</td>
                <td class="text-center">{{ $emp->dias_trabajados }}</td>

                {{-- INGRESOS --}}
                <td class="text-right">{{ number_format($emp->salario_quincenal,2) }}</td>
                <td class="text-center">{{ $emp->horas_extra_cantidad }}</td>
                <td class="text-right">{{ number_format($emp->horas_extra_monto,2) }}</td>

                <td class="text-center">{{ $emp->dias_subsidio }}</td>
                <td class="text-right">{{ number_format($emp->subsidio_monto,2) }}</td>

                <td class="text-right">{{ number_format($emp->feriado,2) }}</td>

                <td class="text-right fw-bold">
                    {{ number_format($emp->total_devengado,2) }}
                </td>

                {{-- DEDUCCIONES --}}
                <td class="text-right">
                    {{ number_format($emp->detalle_inss,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($emp->detalle_ir,2) }}
                </td>

                <td class="text-right fw-bold">
                    {{ number_format($emp->total_deduccion,2) }}
                </td>

                {{-- NETO --}}
                <td class="text-right fw-bold">
                    {{ number_format($emp->neto_pagar,2) }}
                </td>

                {{-- APORTES --}}
                <td class="text-right">
                    {{ number_format($emp->detalle_inatec,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($emp->detalle_inss_patronal,2) }}
                </td>

            </tr>
            @endforeach

        @endforeach

    </tbody>

</table>

<br>

{{-- 🔥 TOTALES --}}
<table>
    <tr>
        <th>Total Devengado</th>
        <th>Total Deducciones</th>
        <th>Total Neto</th>
        <th>Costo Empresa</th>
    </tr>
    <tr>
        <td>{{ number_format($nomina->total_devengado,2) }}</td>
        <td>{{ number_format($nomina->total_deducciones,2) }}</td>
        <td>{{ number_format($nomina->total_neto,2) }}</td>
        <td>{{ number_format($nomina->total_empresa,2) }}</td>
    </tr>
</table>

</body>
</html>