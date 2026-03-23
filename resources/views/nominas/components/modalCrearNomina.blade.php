<!-- 🔥 MODAL CREAR NÓMINA -->
<div class="modal fade" id="modalCrearNomina" tabindex="-1">
<div class="modal-dialog modal-md">
<div class="modal-content border-0 shadow-lg rounded-4">

    {{-- HEADER --}}
    <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
            🧾 Nueva Nómina
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>

    {{-- FORM --}}
    <form id="formCrearNomina" method="POST" action="{{ route('nominas.preview') }}">
        @csrf

        <div class="modal-body">

            <div class="row">

                {{-- FECHA INICIO --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Fecha Inicio</label>
                    <input type="date"
                           name="fecha_inicio"
                           id="fechaInicio"
                           class="form-control shadow-sm"
                           required>
                </div>

                {{-- FECHA FIN --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Fecha Fin</label>
                    <input type="date"
                           name="fecha_fin"
                           id="fechaFin"
                           class="form-control shadow-sm"
                           required>
                </div>

            </div>

            {{-- TIPO --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipo de Nómina</label>

                <select name="tipo"
                        id="tipoNomina"
                        class="form-control shadow-sm">

                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>

                </select>
            </div>

            {{-- INFO UX --}}
            <div class="alert alert-light border small mb-0">
                ⚠️ Se generará una vista previa antes de guardar la nómina.
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="modal-footer">

            <button type="button"
                    class="btn btn-outline-secondary rounded-pill"
                    data-bs-dismiss="modal">
                Cancelar
            </button>

            <button type="submit"
                    class="btn btn-primary rounded-pill shadow-sm">
                👁️ Generar Preview
            </button>

        </div>

    </form>

</div>
</div>
</div>