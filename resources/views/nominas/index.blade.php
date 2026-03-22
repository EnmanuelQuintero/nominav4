@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- 🔥 RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Nóminas</h6>
                <h3 class="fw-bold">12</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Pagado</h6>
                <h3 class="fw-bold text-success">C$ 350,000</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Empleados</h6>
                <h3 class="fw-bold">25</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Horas Extras</h6>
                <h3 class="fw-bold text-warning">120h</h3>
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
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Periodo</th>
                        <th>Fecha</th>
                        <th>Empleados</th>
                        <th>Total Pagado</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @for($i = 1; $i <= 6; $i++)
                    <tr>

                        <td>#00{{ $i }}</td>

                        <td>
                            <strong>Marzo 2026</strong>
                            <br>
                            <small class="text-muted">
                                Quincena {{ $i % 2 == 0 ? '2' : '1' }}
                            </small>
                        </td>

                        <td>2026-03-{{ 10 + $i }}</td>

                        <td>{{ rand(20,30) }}</td>

                        <td class="fw-bold text-success">
                            C$ {{ number_format(rand(200000,400000), 2) }}
                        </td>

                        <td>
                            @if($i % 2 == 0)
                                <span class="badge bg-success">Pagada</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('nominas.preview') }}" 
                            class="btn btn-sm btn-primary rounded-pill">
                                Ver
                            </a>

                                <button class="btn btn-sm btn-outline-success rounded-pill">
                                    Detalle
                                </button>

                                <button class="btn btn-sm btn-outline-secondary rounded-pill">
                                    PDF
                                </button>

                            </div>
                        </td>

                    </tr>
                    @endfor

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection