    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <strong>Listado de Áreas</strong>
        </div>

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($areas as $area)

                        <tr>
                            <td>{{ $area->id }}</td>

                            <td>
                                <strong>{{ $area->nombre }}</strong>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    Editar
                                </button>

                                <button class="btn btn-sm btn-outline-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No hay áreas registradas
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>