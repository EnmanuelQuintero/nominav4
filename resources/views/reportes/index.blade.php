@extends('layouts.app')

@section('content')

<div class="container">

    <div class="mb-4">
        <h4 class="fw-bold">Reportes</h4>
        <small class="text-muted">Seleccione el tipo de reporte que desea generar</small>
    </div>

    <div class="row g-4">

        {{-- 🔹 SOLVENCIAS --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 reporte-card">

                <div class="card-body text-center p-4">

                    <h5 class="fw-semibold mb-2">Solvencias de Pago</h5>

                    <p class="text-muted small mb-4">
                        Genera comprobantes individuales de pago para empleados
                    </p>

                    <button class="btn btn-primary w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#modalSolvencias">
                        Generar
                    </button>

                </div>

            </div>
        </div>



    </div>

</div>

@include('reportes.partials.modal-solvencias')
    @pushOnce('scripts')
        <script src="{{ asset('js/reportes/index.js') }}"></script>
    @endPushOnce
@endsection