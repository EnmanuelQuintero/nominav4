            <table class="table table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cargo</th>
                        <th>Área</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($cargos as $cargo)

                    <tr>
                        <td>{{ $cargo->id }}</td>

                        <td>
                            <strong>{{ $cargo->nombre }}</strong>
                        </td>

                        <td>
                            {{ $cargo->area->nombre }}
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
                        <td colspan="4" class="text-center py-4 text-muted">
                            No hay cargos registrados
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>