@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


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