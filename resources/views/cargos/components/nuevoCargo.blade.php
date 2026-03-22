<form action="{{ route('cargos.store') }}" method="POST">

@csrf

<div class="modal fade" id="modalCrearCargo" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nuevo Cargo</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nombre del Cargo</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Área</label>

                    <select name="area_id"
                            class="form-control"
                            required>

                        <option value="">Seleccione un área</option>

                        @foreach($areas as $area)

                            <option value="{{ $area->id }}">
                                {{ $area->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-success">
                    Guardar
                </button>

            </div>

        </div>
    </div>

</div>

</form>
