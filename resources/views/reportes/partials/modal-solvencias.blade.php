<div class="modal fade" id="modalSolvencias" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Generar Comprobantes de Pago
                </h5>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="row">

                    {{-- AÑO --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Año
                        </label>

                        <select id="anio"
                                class="form-select">

                            <option value="">
                                Seleccione
                            </option>

                            @for($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}">
                                    {{ $i }}
                                </option>
                            @endfor

                        </select>

                    </div>

                    {{-- MES --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Mes
                        </label>

                        <select id="mes"
                                class="form-select">

                            <option value="">
                                Seleccione
                            </option>

                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>

                        </select>

                    </div>

                    {{-- NOMINA --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Nómina
                        </label>

                        <select id="selectNomina"
                                class="form-select">

                            <option value="">
                                Seleccione año y mes
                            </option>

                            @foreach($nominas as $nomina)

                                <option
                                    value="{{ $nomina->id }}"
                                    data-anio="{{ \Carbon\Carbon::parse($nomina->fecha_inicio)->year }}"
                                    data-mes="{{ \Carbon\Carbon::parse($nomina->fecha_inicio)->month }}"
                                >

                                    {{ $nomina->codigo }}
                                    |
                                    {{ \Carbon\Carbon::parse($nomina->fecha_inicio)->format('d/m/Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($nomina->fecha_fin)->format('d/m/Y') }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- TIPO --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Tipo de Reporte
                        </label>

                        <select id="tipoReporte"
                                class="form-select">

                            <option value="todos">
                                Todos los empleados
                            </option>

                            <option value="area">
                                Por área
                            </option>

                            <option value="empleado">
                                Empleado individual
                            </option>

                        </select>

                    </div>

                </div>

                {{-- AREA --}}
                <div class="row">

                    <div class="col-md-6 mb-3 d-none"
                         id="contenedorArea">

                        <label class="form-label">
                            Área
                        </label>

                        <select id="area"
                                class="form-select">

                            <option value="">
                                Seleccione área
                            </option>

                        </select>

                    </div>

                    {{-- EMPLEADO --}}
                    <div class="col-md-6 mb-3 d-none"
                         id="contenedorEmpleado">

                        <label class="form-label">
                            Empleado
                        </label>

<div 
    id="empleado"
    class="border rounded p-3"
    style="max-height:300px; overflow:auto">

    <p class="text-muted">
        Seleccione una nómina
    </p>

</div>

                    </div>

                </div>

                {{-- RESUMEN --}}
                <div class="alert alert-info">

                    <strong>Opciones:</strong>

                    <ul class="mb-0 mt-2">
                        <li>
                            Todos los empleados de una nómina.
                        </li>

                        <li>
                            Todos los empleados de un área.
                        </li>

                        <li>
                            Un comprobante individual.
                        </li>
                    </ul>

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Cancelar

                </button>

                <button type="button"
                        class="btn btn-success"
                        onclick="generarSolvencias()">

                    <i class="bi bi-file-earmark-pdf"></i>
                    Generar PDF

                </button>

            </div>

        </div>
    </div>
</div>
