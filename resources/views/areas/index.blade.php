@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Áreas</h4>
        <small class="text-muted">Gestión de áreas de la empresa</small>
    </div>

    {{-- Tarjetas resumen --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total de Áreas</h6>
                    <h3 class="mb-0">{{ $areas->count() }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabla de áreas --}}
    @include("areas.components.tabla")

</div>


{{-- BOTÓN FLOTANTE --}}
<button class="btn btn-primary btn-lg shadow rounded-circle fab"
        data-bs-toggle="modal"
        data-bs-target="#modalCrearArea">
    +
</button>


{{-- MODAL CREAR AREA --}}
@include("areas.components.nuevaArea")

@endsection