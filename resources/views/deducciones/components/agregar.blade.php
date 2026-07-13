<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasDeduccion">

    <div class="offcanvas-header">

    <h5 class="fw-bold" id="tituloDeduccion">
        Nueva Deducción
    </h5>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
        </button>

    </div>


    <div class="offcanvas-body">


    <form id="formDeduccion"
        action="{{ route('deducciones.store') }}"
        method="POST">

        @csrf

        <input type="hidden" 
            name="_method" 
            id="methodDeduccion"
            value="POST">


        <div class="mb-3">

            <label class="form-label">
                Nombre de la deducción
            </label>

            <input type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                required>

        </div>


        <div class="mb-3">

            <label class="form-label">
                Tipo
            </label>

            <select name="tipo"
                    id="tipo"
                    class="form-select"
                    required>

                <option value="monto">
                    Monto fijo
                </option>

                <option value="porcentaje">
                    Porcentaje
                </option>

            </select>

        </div>


        <div class="mb-3">

            <label class="form-label"
                id="labelValor">

                Valor

            </label>


            <input type="number"
                step="0.01"
                id="valor"
                name="valor"
                class="form-control"
                required>

        </div>


        <div class="mb-3">

            <label class="form-label">
                Descripción
            </label>

            <textarea id="descripcion"
                    name="descripcion"
                    class="form-control"></textarea>

        </div>


        <button class="btn btn-success w-100">

            Guardar

        </button>


    </form>

    </div>

</div>