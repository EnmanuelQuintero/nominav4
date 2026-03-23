@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- 🔥 TITULO --}}
    <div class="mb-4">
        <h4 class="fw-bold">⚙️ Configuración de Nómina</h4>
        <small class="text-muted">Gestiona porcentajes e impuestos del sistema</small>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        {{-- 🔵 PARAMETROS --}}
        <div class="col-lg-4">
            <div class="card shadow border-0 h-100">

                <div class="card-header bg-primary text-white fw-semibold">
                    Parámetros Generales
                </div>

                <div class="card-body">

                    <form action="{{ route('config.nomina.parametros') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">INSS Laboral (%)</label>
                            <input type="number" step="0.01" name="porcentaje_inss_laboral"
                                   class="form-control shadow-sm"
                                   value="{{ $parametros->porcentaje_inss_laboral ?? 0 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">INSS Patronal (%)</label>
                            <input type="number" step="0.01" name="porcentaje_inss_patronal"
                                   class="form-control shadow-sm"
                                   value="{{ $parametros->porcentaje_inss_patronal ?? 0 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">INATEC (%)</label>
                            <input type="number" step="0.01" name="porcentaje_inatec"
                                   class="form-control shadow-sm"
                                   value="{{ $parametros->porcentaje_inatec ?? 0 }}">
                        </div>

                        <button class="btn btn-primary w-100 rounded-pill shadow-sm">
                            💾 Guardar Parámetros
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- 🟡 TABLA IR --}}
        <div class="col-lg-8">

            {{-- FORM IR --}}
            <div class="card shadow border-0 mb-4">

                <div class="card-header bg-success text-white fw-semibold">
                    Agregar fila IR
                </div>

                <div class="card-body">

                    <form action="{{ route('config.nomina.ir') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label">Desde</label>
                                <input type="number" step="0.01" name="desde"
                                       class="form-control shadow-sm"
                                       value="0">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Hasta</label>
                                <input type="number" step="0.01" name="hasta"
                                       class="form-control shadow-sm"
                                       placeholder="∞">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Porcentaje (%)</label>
                                <input type="number" step="0.01" name="porcentaje"
                                       class="form-control shadow-sm"
                                       value="0">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Base</label>
                                <input type="number" step="0.01" name="base"
                                       class="form-control shadow-sm"
                                       value="0">
                            </div>

                        </div>

                        <button class="btn btn-success mt-3 rounded-pill shadow-sm">
                            ➕ Agregar 
                        </button>

                    </form>

                </div>
            </div>

            {{-- TABLA --}}
            <div class="card shadow border-0">

                <div class="card-header bg-dark text-white fw-semibold">
                    Tabla IR
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle text-center mb-0">

                            <thead class="table-light">
                                <tr class="text-muted small">
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>%</th>
                                    <th>Base</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($tablaIR as $ir)
                                <tr>

                                    <td class="fw-semibold">
                                        C$ {{ number_format($ir->desde,2) }}
                                    </td>

                                    <td>
                                        {{ $ir->hasta ? 'C$ '.number_format($ir->hasta,2) : '∞' }}
                                    </td>

                                    <td class=" fw-semibold">
                                        {{ $ir->porcentaje }}%
                                    </td>

                                    <td class=" fw-semibold">
                                        C$ {{ number_format($ir->base,2) }}
                                    </td>

                                    <td>
                                        <form action="{{ route('config.nomina.ir.delete',$ir->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger rounded-pill">
                                                🗑
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-muted">
                                        No hay tramos configurados
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection