<div class="modal fade" id="modalDias" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">
    📅 Control de Días Trabajados
</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row">

    <!-- ===================== -->
    <!-- CALENDARIO -->
    <!-- ===================== -->
    <div class="col-md-7">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <button class="btn-nav-calendario" onclick="cambiarMes(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <strong id="tituloCalendario" class="titulo-calendario"></strong>

            <button class="btn-nav-calendario" onclick="cambiarMes(1)">
                <i class="bi bi-chevron-right"></i>
            </button>

        </div>

        <div class="row text-center fw-bold mb-2">
            <div class="col">L</div>
            <div class="col">M</div>
            <div class="col">X</div>
            <div class="col">J</div>
            <div class="col">V</div>
            <div class="col">S</div>
            <div class="col">D</div>
        </div>

        <div id="calendario" class="calendario-grid"></div>

    </div>


    <!-- ===================== -->
    <!-- OPCIONES -->
    <!-- ===================== -->
    <div class="col-md-5">

        <!-- Estado -->
        <div class="mb-3">
            <label class="form-label">Estado del día</label>

            <select class="form-select" id="estadoDia" onchange="verOpciones()" disabled>
                <option value="">Seleccione</option>
                <option value="trabajado">Trabajado</option>
                <option value="vacaciones">Vacaciones</option>
                <option value="compensado">Compensado</option>
                <option value="no_trabajado">No trabajado</option>
            </select>
        </div>


        <!-- HORAS EXTRAS -->
        <div id="opcionHoras" style="display:none">

            <div class="row align-items-center">

                <!-- Switch -->
                <div class="col-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="switchHoras"
                               onchange="mostrarInputHoras()">
                        <label class="form-check-label">
                            Horas extras
                        </label>
                    </div>
                </div>

                <!-- Input -->
                <div class="col-6" id="inputHoras" style="display:none">
                    <input type="number"
                        name="horas"
                        class="form-control"
                        placeholder="Horas"
                        min="0"
                        step="0.5">
                </div>

            </div>

        </div>

        <!-- BOTÓN -->
        <button class="btn btn-success mt-4 w-100 rounded-pill shadow-sm" onclick="guardarDia()">
            Aplicar
        </button>

    </div>

</div>

</div>

</div>


</div>
</div>
</div>