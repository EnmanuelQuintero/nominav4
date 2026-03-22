@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- 🔥 RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Devengado</h6>
                <h4 class="text-success">C$ 85,000</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Deducciones</h6>
                <h4 class="text-danger">C$ 12,500</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Neto a Pagar</h6>
                <h4 class="text-primary">C$ 72,500</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Costo Empresa</h6>
                <h4 class="text-warning">C$ 100,000</h4>
            </div>
        </div>

    </div>

    {{-- 🔥 HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold">Listado de Nóminas</h5>

        <button class="btn btn-success rounded-pill shadow-sm">
            + Nueva Nómina
        </button>

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

                    @php
                    $areas = [
                        'Administración',
                        'Recursos Humanos',
                        'Contabilidad'
                    ];
                    @endphp

                    @foreach($areas as $area)

                        {{-- 🔵 TITULO DEL AREA --}}
                        <tr class="fila-area">
                            <td colspan="22" class="text-start fw-bold">
                                🏢 {{ $area }}
                            </td>
                        </tr>

                        {{-- 🔥 EMPLEADOS DEL AREA --}}
                        @for($i = 1; $i <= 3; $i++)
                        <tr>

                            {{-- EMPLEADO --}}
                            <td>EMP-{{ rand(100,999) }}</td>

                            <td class="text-start">
                                <strong>{{ $area }} - Emp {{ $i }}</strong>
                            </td>

                            <td>Administrador</td>
                            <td>12345678</td>
                            <td>C$ 15,000</td>
                            <td>C$ 500</td>
                            <td>13</td>

                            {{-- INGRESOS --}}
                            <td>C$ 6,500</td>
                            <td>5</td>
                            <td class="text-success">C$ 1,200</td>
                            <td>2</td>
                            <td class="text-info">C$ 800</td>
                            <td>C$ 300</td>
                            <td class="fw-bold text-success">C$ 8,800</td>

                            {{-- DEDUCCIONES --}}
                            <td class="text-danger">C$ 616</td>
                            <td class="text-danger">C$ 300</td>
                            <td class="fw-bold text-danger">C$ 916</td>

                            {{-- NETO --}}
                            <td class="fw-bold text-primary">C$ 7,884</td>

                            {{-- APORTES --}}
                            <td class="text-warning">C$ 176</td>
                            <td class="text-warning">C$ 1,980</td>

                            {{-- FIRMA --}}
                            <td style="min-width:140px;">
                                <div style="height:45px; border-bottom:1px solid #000;"></div>
                                <small class="text-muted">Recibí conforme</small>
                            </td>

                        </tr>
                        @endfor

                        {{-- 🟣 SUBTOTAL POR AREA --}}
                        <tr class="fila-subtotal">
                            <td colspan="13"></td>

                            <td class="fw-bold text-success">
                                C$ 26,400
                            </td>

                            <td colspan="2"></td>

                            <td class="fw-bold text-danger">
                                C$ 2,748
                            </td>

                            <td class="fw-bold text-primary">
                                C$ 23,652
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