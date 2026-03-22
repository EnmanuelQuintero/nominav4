@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h4>Cargos</h4>
    </div>

    <div class="card shadow-sm">

        <div class="card-header">
            Listado de Cargos
        </div>

        <div class="card-body p-0">
            {{-- Tabla de datos --}}
            @include("cargos.components.tabla")

        </div>

    </div>

</div>


{{-- BOTON FLOTANTE --}}
<button class="btn btn-primary btn-lg shadow rounded-circle fab"
        data-bs-toggle="modal"
        data-bs-target="#modalCrearCargo">
    +
</button>



{{-- MODAL CREAR CARGO --}}
@include("cargos.components.nuevoCargo")

@endsection