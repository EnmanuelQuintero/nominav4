@extends('layouts.app')

@section('content')

<div class="card shadow-sm">
    <div class="mb-3">
        <h5 class="fw-bold">
            Nómina: {{ $nomina->codigo ?? 'Sin código' }}
        </h5>

        <small class="text-muted">
            Periodo: {{ \Carbon\Carbon::parse($nomina->fecha_inicio)->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($nomina->fecha_fin)->format('d/m/Y') }}
        </small>
    </div>

    {{-- 🔥 RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                <h6 class="text-muted">Total Devengado</h6>
                <h4 class="fw-bold text-success">
                    C$ {{ number_format($nomina->total_devengado, 2) }}
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                <h6 class="text-muted">Total Deducciones</h6>
                <h4 class="fw-bold text-danger">
                    C$ {{ number_format($nomina->total_deducciones, 2) }}
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                <h6 class="text-muted">Total Neto</h6>
                <h4 class="fw-bold text-primary">
                    C$ {{ number_format($nomina->total_neto, 2) }}
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                <h6 class="text-muted">Costo Empresa</h6>
                <h4 class="fw-bold text-warning">
                    C$ {{ number_format($nomina->total_empresa, 2) }}
                </h4>
            </div>
        </div>

    </div>
    <div class="card-body p-0">

        <div class="table-responsive tabla-nomina-scroll">
            <table class="table table-bordered table-hover align-middle text-center mb-0 tabla-nomina">

                {{-- 🔥 CABECERA --}}
                <thead>

                    <tr class="table-dark">
                        <th colspan="7">Empleado</th>
                        <th colspan="7" class="table-success">Ingresos</th>
                        <th colspan="3" class="table-danger">Deducciones</th>
                        <th colspan="1" class="table-primary">Neto</th>
                        <th colspan="2" class="table-warning">Aportes</th>
                    </tr>

                    <tr class="table-secondary">

                        {{-- EMPLEADO --}}
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>INSS</th>
                        <th>Sal. Mensual</th>
                        <th>Sal. Diario</th>
                        <th>Días Trab.</th>

                        {{-- INGRESOS --}}
                        <th>Sal. Quincenal</th>
                        <th>Hrs Extra</th>
                        <th>Monto Hrs</th>
                        <th>Días Subsidio</th>
                        <th>Subsidio</th>
                        <th>Feriado</th>
                        <th>Total Devengado</th>

                        {{-- DEDUCCIONES --}}
                        <th>INSS</th>
                        <th>IR</th>
                        <th>Total</th>

                        {{-- NETO --}}
                        <th>Neto</th>

                        {{-- APORTES --}}
                        <th>INATEC</th>
                        <th>INSS Patronal</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($detallesAgrupados as $grupo => $empleados)

                        {{-- 🔵 GRUPO --}}
                        <tr class="table-light">
                            <td colspan="20" class="text-start fw-bold">
                                🏢 {{ $grupo }}
                            </td>
                        </tr>

                        @foreach($empleados as $emp)
                        <tr>

                            {{-- EMPLEADO --}}
                            <td>{{ $emp->numero_empleado }}</td>
                            <td class="text-start">{{ $emp->nombre }}</td>
                            <td>{{ $emp->cargo }}</td>
                            <td>{{ $emp->inss }}</td>

                            <td>C$ {{ number_format($emp->salario_mensual,2) }}</td>
                            <td>C$ {{ number_format($emp->salario_diario,2) }}</td>
                            <td>{{ $emp->dias_trabajados }}</td>

                            {{-- INGRESOS --}}
                            <td>C$ {{ number_format($emp->salario_quincenal,2) }}</td>
                            <td>{{ $emp->horas_extra_cantidad }}</td>
                            <td class="text-success">
                                C$ {{ number_format($emp->horas_extra_monto,2) }}
                            </td>

                            <td>{{ $emp->dias_subsidio }}</td>
                            <td class="text-info">
                                C$ {{ number_format($emp->subsidio_monto,2) }}
                            </td>

                            <td>C$ {{ number_format($emp->feriado,2) }}</td>

                            <td class="fw-bold text-success">
                                C$ {{ number_format($emp->total_devengado,2) }}
                            </td>

                            {{-- DEDUCCIONES --}}
                            <td class="text-danger">
                                C$ {{ number_format($emp->detalle_inss,2) }}
                            </td>

                            <td class="text-danger">
                                C$ {{ number_format($emp->detalle_ir,2) }}
                            </td>

                            <td class="fw-bold text-danger">
                                C$ {{ number_format($emp->total_deduccion,2) }}
                            </td>

                            {{-- NETO --}}
                            <td class="fw-bold text-primary">
                                C$ {{ number_format($emp->neto_pagar,2) }}
                            </td>

                            {{-- APORTES --}}
                            <td class="text-warning">
                                C$ {{ number_format($emp->detalle_inatec,2) }}
                            </td>

                            <td class="text-warning">
                                C$ {{ number_format($emp->detalle_inss_patronal,2) }}
                            </td>

                        </tr>
                        @endforeach

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection