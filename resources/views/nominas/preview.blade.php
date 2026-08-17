@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/nomina/tabla.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a
                    href="{{ route('nominas.index') }}"
                    class="btn btn-light btn-sm border"
                    title="Volver"
                >
                    ←
                </a>

                <h3 class="fw-bold mb-0">Previsualización de nómina</h3>
            </div>

            <p class="text-muted mb-0">
                Período:
                <strong>
                    {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
                    al
                    {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                </strong>
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a
                href="{{ route('nominas.index') }}"
                class="btn btn-outline-secondary px-4"
            >
                Cancelar
            </a>

            <form
                id="formGuardarNomina"
                action="{{ route('nominas.store') }}"
                method="POST"
            >
                @csrf

                <textarea name="detalles" hidden>{{ json_encode($detallesAgrupados) }}</textarea>

                <input
                    type="hidden"
                    name="fecha_inicio"
                    value="{{ $fechaInicio }}"
                >

                <input
                    type="hidden"
                    name="fecha_fin"
                    value="{{ $fechaFin }}"
                >

                @if($nominaExistente)
                    <input type="hidden" name="actualizar" value="1">

                    <input
                        type="hidden"
                        name="nomina_id"
                        value="{{ $nominaExistente->id }}"
                    >
                @endif

                <button
                    type="submit"
                    id="btnGuardarNomina"
                    class="btn {{ $nominaExistente ? 'btn-warning' : 'btn-success' }} px-4"
                >
                    <span class="texto-boton">
                        {{ $nominaExistente ? 'Actualizar nómina' : 'Guardar nómina' }}
                    </span>

                    <span
                        class="spinner-border spinner-border-sm d-none"
                        role="status"
                        aria-hidden="true"
                    ></span>
                </button>
            </form>
        </div>
    </div>

    {{-- ADVERTENCIA --}}
    @if($nominaExistente)
        <div class="alert alert-warning nomina-alerta shadow-sm" role="alert">
            <div class="d-flex gap-3">
                <div class="alerta-icono">!</div>

                <div>
                    <h6 class="fw-bold mb-1">Este período ya posee una nómina</h6>

                    <p class="mb-0">
                        Se encontró la nómina
                        <strong>{{ $nominaExistente->codigo }}</strong>.
                        Al continuar, sus detalles actuales serán reemplazados
                        por los resultados de esta previsualización.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ERRORES --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>No se pudo procesar la nómina:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INFORMACIÓN GENERAL --}}
    @php
        $cantidadEmpleados = collect($detallesAgrupados)
            ->flatten(1)
            ->count();

        $cantidadAreas = $detallesAgrupados->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl">
            <div class="card tarjeta-resumen tarjeta-devengado h-100">
                <div class="card-body">
                    <span class="resumen-etiqueta">Total devengado</span>

                    <h4 class="resumen-monto">
                        C$ {{ number_format($resumen['devengado'], 2) }}
                    </h4>

                    <small>Ingresos antes de deducciones</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl">
            <div class="card tarjeta-resumen tarjeta-deducciones h-100">
                <div class="card-body">
                    <span class="resumen-etiqueta">Deducciones</span>

                    <h4 class="resumen-monto">
                        C$ {{ number_format($resumen['deducciones'], 2) }}
                    </h4>

                    <small>INSS, IR y otras deducciones</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl">
            <div class="card tarjeta-resumen tarjeta-neto h-100">
                <div class="card-body">
                    <span class="resumen-etiqueta">Neto a pagar</span>

                    <h4 class="resumen-monto">
                        C$ {{ number_format($resumen['neto'], 2) }}
                    </h4>

                    <small>Monto total para empleados</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl">
            <div class="card tarjeta-resumen tarjeta-empresa h-100">
                <div class="card-body">
                    <span class="resumen-etiqueta">Aportes empresa</span>

                    <h4 class="resumen-monto">
                        C$ {{ number_format($resumen['empresa'], 2) }}
                    </h4>

                    <small>INATEC e INSS patronal</small>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl">
            <div class="card tarjeta-resumen tarjeta-empleados h-100">
                <div class="card-body">
                    <span class="resumen-etiqueta">Empleados</span>

                    <h4 class="resumen-monto">
                        {{ $cantidadEmpleados }}
                    </h4>

                    <small>{{ $cantidadAreas }} áreas incluidas</small>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm nomina-card">
        <div class="card-header bg-white border-0 px-4 py-3">
            <div class="row align-items-center g-3">
                <div class="col-md">
                    <h5 class="fw-bold mb-1">Detalle por empleado</h5>

                    <small class="text-muted">
                        Revise los resultados antes de guardar la nómina.
                    </small>
                </div>

                <div class="col-md-auto">
                    <div class="input-group buscador-nomina">
                        <span class="input-group-text bg-white">⌕</span>

                        <input
                            type="search"
                            id="buscarEmpleado"
                            class="form-control"
                            placeholder="Buscar empleado, cargo o área"
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="tabla-nomina-scroll">
                <table
                    id="tablaNomina"
                    class="table table-hover align-middle mb-0 tabla-nomina"
                >
                    <thead>
                        {{-- 22 columnas en total --}}
                        <tr class="encabezado-grupos">
                            <th colspan="7" class="grupo-empleado">
                                Empleado
                            </th>

                            <th colspan="7" class="grupo-ingresos">
                                Ingresos
                            </th>

                            <th colspan="4" class="grupo-deducciones">
                                Deducciones
                            </th>

                            <th colspan="1" class="grupo-neto">
                                Neto
                            </th>

                            <th colspan="2" class="grupo-aportes">
                                Aportes de la empresa
                            </th>

                            <th colspan="1" class="grupo-firma">
                                Firma
                            </th>
                        </tr>

                        <tr class="encabezado-columnas">
                            {{-- EMPLEADO: 7 --}}
                            <th>N.º</th>
                            <th class="columna-nombre">Nombre</th>
                            <th>Cargo</th>
                            <th>N.º INSS</th>
                            <th>Salario mensual</th>
                            <th>Salario diario</th>
                            <th>Días trabajados</th>

                            {{-- INGRESOS: 7 --}}
                            <th>Salario período</th>
                            <th>Horas extra</th>
                            <th>Monto horas</th>
                            <th>Días subsidio</th>
                            <th>Subsidio</th>
                            <th>Feriado</th>
                            <th>Total devengado</th>

                            {{-- DEDUCCIONES: 4 --}}
                            <th>INSS laboral</th>
                            <th>IR</th>
                            <th>Otras</th>
                            <th>Total deducciones</th>

                            {{-- NETO: 1 --}}
                            <th>Neto a pagar</th>

                            {{-- APORTES: 2 --}}
                            <th>INATEC</th>
                            <th>INSS patronal</th>

                            {{-- FIRMA: 1 --}}
                            <th>Firma</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($detallesAgrupados as $area => $empleados)
                            @php
                                $subDevengado = collect($empleados)->sum('devengado');
                                $subDeduccion = collect($empleados)->sum('deduccion');
                                $subNeto = collect($empleados)->sum('neto');
                                $subInatec = collect($empleados)->sum('inatec');
                                $subInssPatronal = collect($empleados)->sum('inss_patronal');
                            @endphp

                            <tr
                                class="fila-area"
                                data-area="{{ mb_strtolower($area) }}"
                            >
                                <td colspan="22">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            {{ $area }}
                                        </span>

                                        <span class="badge rounded-pill">
                                            {{ count($empleados) }}
                                            {{ count($empleados) === 1 ? 'empleado' : 'empleados' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            @foreach($empleados as $emp)
                                <tr
                                    class="fila-empleado"
                                    data-area="{{ mb_strtolower($area) }}"
                                    data-busqueda="{{ mb_strtolower(
                                        $area . ' ' .
                                        $emp['numero'] . ' ' .
                                        $emp['nombre'] . ' ' .
                                        $emp['cargo'] . ' ' .
                                        ($emp['inss'] ?? '')
                                    ) }}"
                                >
                                    <td>
                                        <span class="badge-numero">
                                            {{ $emp['numero'] }}
                                        </span>
                                    </td>

                                    <td class="text-start columna-nombre">
                                        <strong>{{ $emp['nombre'] }}</strong>
                                    </td>

                                    <td>{{ $emp['cargo'] }}</td>

                                    <td>{{ $emp['inss'] ?: '—' }}</td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['salario_mensual'], 2) }}
                                    </td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['salario_diario'], 2) }}
                                    </td>

                                    <td>{{ $emp['dias_trabajados'] }}</td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['salario_quincenal'], 2) }}
                                    </td>

                                    <td>{{ $emp['horas_extra'] }}</td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['monto_horas'], 2) }}
                                    </td>

                                    <td>{{ $emp['dias_subsidio'] }}</td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['subsidio'], 2) }}
                                    </td>

                                    <td class="monto">
                                        C$ {{ number_format($emp['feriado'], 2) }}
                                    </td>

                                    <td class="monto monto-devengado">
                                        C$ {{ number_format($emp['devengado'], 2) }}
                                    </td>

                                    <td class="monto monto-deduccion">
                                        C$ {{ number_format($emp['inss_deduccion'], 2) }}
                                    </td>

                                    <td class="monto monto-deduccion">
                                        C$ {{ number_format($emp['ir'], 2) }}
                                    </td>

                                    <td class="monto monto-deduccion">
                                        C$ {{ number_format($emp['otras_deducciones'], 2) }}
                                    </td>

                                    <td class="monto monto-deduccion fw-bold">
                                        C$ {{ number_format($emp['deduccion'], 2) }}
                                    </td>

                                    <td class="monto monto-neto">
                                        C$ {{ number_format($emp['neto'], 2) }}
                                    </td>

                                    <td class="monto monto-aporte">
                                        C$ {{ number_format($emp['inatec'], 2) }}
                                    </td>

                                    <td class="monto monto-aporte">
                                        C$ {{ number_format($emp['inss_patronal'], 2) }}
                                    </td>

                                    <td class="columna-firma">
                                        <div class="linea-firma"></div>
                                    </td>
                                </tr>
                            @endforeach

                            <tr
                                class="fila-subtotal"
                                data-area="{{ mb_strtolower($area) }}"
                            >
                                <td colspan="13" class="text-end">
                                    Subtotal de {{ $area }}
                                </td>

                                <td class="monto subtotal-devengado">
                                    C$ {{ number_format($subDevengado, 2) }}
                                </td>

                                <td colspan="3"></td>

                                <td class="monto subtotal-deduccion">
                                    C$ {{ number_format($subDeduccion, 2) }}
                                </td>

                                <td class="monto subtotal-neto">
                                    C$ {{ number_format($subNeto, 2) }}
                                </td>

                                <td class="monto">
                                    C$ {{ number_format($subInatec, 2) }}
                                </td>

                                <td class="monto">
                                    C$ {{ number_format($subInssPatronal, 2) }}
                                </td>

                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="22" class="text-center py-5">
                                    <h6 class="fw-bold">No hay empleados para mostrar</h6>

                                    <p class="text-muted mb-0">
                                        Verifique el período seleccionado y los registros de asistencia.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="filaSinResultados" class="d-none">
                            <td colspan="22" class="text-center py-5">
                                <strong>No se encontraron resultados</strong>

                                <p class="text-muted mb-0">
                                    Intente buscar con otro nombre, número, cargo o área.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white px-4 py-3">
            <small class="text-muted">
                Puede desplazarse horizontalmente para consultar todas las columnas.
            </small>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN --}}
<div
    class="modal fade"
    id="modalConfirmarNomina"
    tabindex="-1"
    aria-labelledby="tituloModalNomina"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="tituloModalNomina">
                    {{ $nominaExistente ? 'Confirmar actualización' : 'Confirmar nómina' }}
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            <div class="modal-body">
                @if($nominaExistente)
                    <p>
                        Se reemplazarán los datos de la nómina
                        <strong>{{ $nominaExistente->codigo }}</strong>.
                    </p>
                @else
                    <p>
                        Se guardará la nómina correspondiente al período seleccionado.
                    </p>
                @endif

                <div class="resumen-confirmacion">
                    <div>
                        <span>Empleados</span>
                        <strong>{{ $cantidadEmpleados }}</strong>
                    </div>

                    <div>
                        <span>Neto a pagar</span>
                        <strong>
                            C$ {{ number_format($resumen['neto'], 2) }}
                        </strong>
                    </div>
                </div>

                <p class="text-muted small mb-0 mt-3">
                    Los cálculos serán verificados nuevamente antes de guardar.
                </p>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Revisar nuevamente
                </button>

                <button
                    type="button"
                    id="btnConfirmarGuardado"
                    class="btn {{ $nominaExistente ? 'btn-warning' : 'btn-success' }}"
                >
                    {{ $nominaExistente ? 'Sí, actualizar' : 'Sí, guardar' }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formGuardarNomina');
    const botonGuardar = document.getElementById('btnGuardarNomina');
    const botonConfirmar = document.getElementById('btnConfirmarGuardado');
    const buscador = document.getElementById('buscarEmpleado');
    const filaSinResultados = document.getElementById('filaSinResultados');

    const modalElemento = document.getElementById('modalConfirmarNomina');
    const modal = new bootstrap.Modal(modalElemento);

    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!formulario.dataset.confirmado) {
            modal.show();
            return;
        }

        botonGuardar.disabled = true;
        botonConfirmar.disabled = true;

        botonGuardar.querySelector('.texto-boton').textContent = 'Procesando...';
        botonGuardar.querySelector('.spinner-border').classList.remove('d-none');
    });

    botonConfirmar.addEventListener('click', function () {
        formulario.dataset.confirmado = '1';
        modal.hide();
        formulario.requestSubmit();
    });

    buscador.addEventListener('input', function () {
        const termino = normalizarTexto(this.value);
        const empleados = document.querySelectorAll('.fila-empleado');
        const areas = document.querySelectorAll('.fila-area');
        const subtotales = document.querySelectorAll('.fila-subtotal');

        let resultados = 0;
        const areasVisibles = new Set();

        empleados.forEach(function (fila) {
            const contenido = normalizarTexto(fila.dataset.busqueda);
            const visible = contenido.includes(termino);

            fila.classList.toggle('d-none', !visible);

            if (visible) {
                resultados++;
                areasVisibles.add(fila.dataset.area);
            }
        });

        areas.forEach(function (fila) {
            fila.classList.toggle(
                'd-none',
                !areasVisibles.has(fila.dataset.area)
            );
        });

        subtotales.forEach(function (fila) {
            /*
             * Los subtotales se ocultan durante la búsqueda porque
             * representan el área completa, no solo los resultados visibles.
             */
            fila.classList.toggle('d-none', termino !== '');
        });

        filaSinResultados.classList.toggle(
            'd-none',
            resultados > 0
        );
    });

    function normalizarTexto(texto) {
        return String(texto || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }
});
</script>
@endpush