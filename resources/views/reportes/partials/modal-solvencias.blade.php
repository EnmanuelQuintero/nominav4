<div class="modal fade" id="modalSolvencias" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Generar Solvencias</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- 🔹 SELECCIONAR NOMINA --}}
                <div class="mb-3">
                    <label class="form-label">Seleccionar Nómina</label>
                    <select id="selectNomina" class="form-select">
                        <option value="">Seleccione...</option>

                        @foreach($nominas as $nomina)
                            <option value="{{ $nomina->id }}">
                                {{ $nomina->codigo }} | 
                                {{ \Carbon\Carbon::parse($nomina->fecha_inicio)->format('d/m/Y') }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- 🔹 CONTROLES --}}
                <div class="d-flex justify-content-between mb-2">

                    <div>
                        <button class="btn btn-sm btn-outline-primary" onclick="seleccionarTodos()">
                            Seleccionar todos
                        </button>

                        <button class="btn btn-sm btn-outline-secondary" onclick="limpiarSeleccion()">
                            Limpiar
                        </button>
                    </div>

                </div>

                {{-- 🔹 LISTA EMPLEADOS --}}
                <div id="listaEmpleados" class="border rounded p-3" style="max-height:400px; overflow:auto;">
                    <p class="text-muted text-center">Seleccione una nómina</p>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <button type="button" class="btn btn-success" onclick="generarSolvencias()">
                    Generar PDF
                </button>
            </div>

        </div>
    </div>
</div>