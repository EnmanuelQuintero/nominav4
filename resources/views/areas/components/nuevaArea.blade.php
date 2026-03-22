<form action="{{ route('areas.store') }}" method="POST">

@csrf

<div class="modal fade" id="modalCrearArea" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nueva Área</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nombre del Área</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Ej: Recursos Humanos"
                           required>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
                </button>

            </div>

        </div>
    </div>
</div>

</form>    