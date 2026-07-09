@extends('layouts.app')

@section('content')

@if($nominaExistente)
    <div class="alert alert-warning border-start border-5 border-warning shadow-sm">
        <strong>Atención:</strong>

        Ya existe una nómina registrada para este período
        ({{ $nominaExistente->codigo }}).

        Si continúa, los datos existentes serán reemplazados por los de esta previsualización.
    </div>
@endif

    @push('css')
        <link rel="stylesheet" href="{{ asset('css/nomina/tabla.css') }}">
    @endpush
<div class="container-fluid mt-4">

    {{-- 🔥 RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Devengado</h6>
                <h4 class="text-success">C$ {{ number_format($resumen['devengado'], 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Deducciones</h6>
                <h4 class="text-danger">C$ {{ number_format($resumen['deducciones'], 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Neto a Pagar</h6>
                <h4 class="text-primary">C$ {{ number_format($resumen['neto'], 2) }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Costo Empresa</h6>
                <h4 class="text-warning">C$ {{ number_format($resumen['empresa'], 2) }}</h4>
            </div>
        </div>

    </div>

    {{-- 🔥 HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold">Listado de Nóminas</h5>

        {{-- 🔥 BOTÓN GUARDAR NÓMINA --}}
        <form action="{{ route('nominas.store') }}" method="POST"  onsubmit="this.querySelector('button[type=submit]').disabled=true;">
            @csrf

            <input type="hidden" name="detalles" value='@json($detallesAgrupados)'>
            <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
            <input type="hidden" name="fecha_fin" value="{{ $fechaFin }}">

            @if($nominaExistente)
                <input type="hidden" name="actualizar" value="1">
                <input type="hidden" name="nomina_id" value="{{ $nominaExistente->id }}">
            @endif

        <button
            type="submit"
            class="btn {{ $nominaExistente ? 'btn-warning' : 'btn-success' }} rounded-pill shadow-sm">
            {{ $nominaExistente ? 'Actualizar Nómina' : 'Guardar Nómina' }}
        </button>

        </form>

    </div>

    {{-- 🔥 TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="tabla-nomina-scroll">
                <table class="table table-bordered table-hover align-middle text-center mb-0 tabla-nomina">
                    <thead>

                        {{-- GRUPOS --}}
                        <tr class="table-dark">
                            <th colspan="7">Empleado</th>
                            <th colspan="9" class="table-success">Ingresos</th>
                            <th colspan="3" class="table-danger">Deducciones</th>
                            <th colspan="1" class="table-primary">Neto</th>
                            <th colspan="2" class="table-warning">Firma</th>
                            
                        </tr>

                        {{-- CAMPOS --}}
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

                    @foreach($detallesAgrupados as $area => $empleados)

                        {{-- 🔵 AREA --}}
                        <tr class="fila-area">
                            <td colspan="22" class="text-start fw-bold">
                                🏢 {{ $area }}
                            </td>
                        </tr>

                        @php
                            $subDevengado = 0;
                            $subDeduccion = 0;
                            $subNeto = 0;
                        @endphp

                        {{-- 🔥 EMPLEADOS --}}
                        @foreach($empleados as $emp)

                        @php
                            $subDevengado += $emp['devengado'];
                            $subDeduccion += $emp['deduccion'];
                            $subNeto += $emp['neto'];
                        @endphp

                        <tr>

                            <td>{{ $emp['numero'] }}</td>

                            <td class="text-start">
                                <strong>{{ $emp['nombre'] }}</strong>
                            </td>

                            <td>{{ $emp['cargo'] }}</td>
                            <td>{{ $emp['inss'] }}</td>

                            <td>C$ {{ number_format($emp['salario_mensual'],2) }}</td>
                            <td>C$ {{ number_format($emp['salario_diario'],2) }}</td>
                            <td>{{ $emp['dias_trabajados'] }}</td>

                            <td>C$ {{ number_format($emp['salario_quincenal'],2) }}</td>
                            <td>{{ $emp['horas_extra'] }}</td>
                            <td>C$ {{ number_format($emp['monto_horas'],2) }}</td>

                            <td>{{ $emp['dias_subsidio'] }}</td>
                            <td>C$ {{ number_format($emp['subsidio'],2) }}</td>

                            <td>C$ {{ number_format($emp['feriado'],2) }}</td>

                            <td class="fw-bold text-success">
                                C$ {{ number_format($emp['devengado'],2) }}
                            </td>

                            <td class="text-danger">
                                C$ {{ number_format($emp['inss_deduccion'],2) }}
                            </td>

                            <td class="text-danger">
                                C$ {{ number_format($emp['ir'],2) }}
                            </td>

                            <td class="fw-bold text-danger">
                                C$ {{ number_format($emp['deduccion'],2) }}
                            </td>

                            <td class="fw-bold text-primary">
                                C$ {{ number_format($emp['neto'],2) }}
                            </td>

                            <td class="text-warning">
                                C$ {{ number_format($emp['inatec'],2) }}
                            </td>

                            <td class="text-warning">
                                C$ {{ number_format($emp['inss_patronal'],2) }}
                            </td>

                            {{-- FIRMA --}}
                            <td style="min-width:140px;">
                                <div style="height:45px; border-bottom:1px solid #000;"></div>
                            </td>

                        </tr>
                        @endforeach

                        {{-- 🟣 SUBTOTAL POR AREA --}}
                        <tr class="fila-subtotal">

                            <td colspan="13"></td>

                            <td class="fw-bold text-success">
                                C$ {{ number_format($subDevengado,2) }}
                            </td>

                            <td colspan="2"></td>

                            <td class="fw-bold text-danger">
                                C$ {{ number_format($subDeduccion,2) }}
                            </td>

                            <td class="fw-bold text-primary">
                                C$ {{ number_format($subNeto,2) }}
                            </td>

                            <td colspan="2"></td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

                </div>
            </div>
    </div>

</div>

@endsection