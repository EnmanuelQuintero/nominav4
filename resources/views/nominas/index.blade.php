@extends('layouts.app')
@section('content')

<div class="container-fluid mt-4">

    {{-- 🔥 RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Nóminas</h6>
                <h3 class="fw-bold">{{ $totalNominas }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Pagado</h6>
                <h3 class="fw-bold text-success">
                    C$ {{ number_format($totalPagado,2) }}
                </h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Empleados</h6>
                <h3 class="fw-bold">{{ $totalEmpleados }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Horas Extras</h6>
                <h3 class="fw-bold text-warning">
                    {{ $totalHorasExtras }}h
                </h3>
            </div>
        </div>

    </div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold">Listado de Nóminas</h5>

        <button class="btn btn-success rounded-pill shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearNomina">
            + Nueva Nómina
        </button>

    </div>

    {{-- TABLA --}}
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

                    @forelse($nominas as $nomina)
                    <tr>

                        <td>
                            <strong>{{ $nomina->codigo ?? 'N/A' }}</strong>
                        </td>

                        <td>
                            <strong>
                                {{ \Carbon\Carbon::parse($nomina->fecha_inicio)->format('F Y') }}
                            </strong>
                            <br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($nomina->fecha_inicio)->day <= 15 ? 'Quincena 1' : 'Quincena 2' }}
                            </small>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($nomina->fecha_fin)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $nomina->detalles->count() ?? 0 }}
                        </td>

                        <td class="fw-bold text-success">
                            C$ {{ number_format($nomina->total_neto, 2) }}
                        </td>

                        <td>
                            @if($nomina->estado == 'Pagada')
                                <span class="badge bg-success">Pagada</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('nominas.show', $nomina->id) }}"
                                class="btn btn-sm btn-primary rounded-pill">
                                    Ver
                                </a>

                                <button class="btn btn-sm btn-outline-success rounded-pill">
                                    Detalle
                                </button>

                                <a href="{{ route('nominas.pdf', $nomina->id) }}"
                                class="btn btn-sm btn-outline-secondary rounded-pill">
                                    PDF
                                </a>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay nóminas registradas
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>


@include("nominas.components.modalCrearNomina")

@endsection