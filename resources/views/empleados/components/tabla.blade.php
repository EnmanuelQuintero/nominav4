<div id="vista-tabla">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-hover align-middle table-empleados">
                
                <thead class="table-dark">
                    <tr>
                        <th>Empleado</th>
                        <th>Área / Cargo</th>
                        <th>Contacto</th>
                        <th>Salario</th>

                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($empleados->groupBy('cargo.area.nombre') as $area => $grupo)

                    {{-- TITULO DEL AREA --}}
                    <tr class="fila-area">
                        <td colspan="6">
                            <strong>{{ $area ?? 'Sin área' }}</strong>
                        </td>
                    </tr>

                    @foreach($grupo as $empleado)

                        <tr class="fila-empleado">

                            {{-- 👤 EMPLEADO --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">

                                @if(Str::startsWith($empleado->foto, 'images/'))
                                    <img src="{{ asset($empleado->foto) }}"
                                        alt="Foto"
                                        class="rounded-circle"
                                        width="80"
                                        height="80"
                                        style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/' . $empleado->foto) }}"
                                        alt="Foto"
                                        class="rounded-circle"
                                        width="80"
                                        height="80"
                                        style="object-fit: cover;">
                                @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ $empleado->nombre }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $empleado->cedula }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            {{-- 🏢 AREA + CARGO --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $empleado->cargo->nombre ?? '' }}
                                </div>
                                <small class="text-muted">
                                    {{ $empleado->cargo->area->nombre ?? '' }}
                                </small>
                            </td>

                            {{-- 📞 CONTACTO --}}
                            <td>
                                <div>{{ $empleado->telefono ?? '—' }}</div>
                                <small class="text-muted">
                                    {{ $empleado->correo ?? '—' }}
                                </small>
                            </td>

                            {{-- 💰 SALARIO --}}
                            <td class="fw-bold text-success">
                                C$ {{ number_format($empleado->salario, 2) }}
                            </td>



                            {{-- 🟢 ESTADO --}}
                            <td>
                                <span class="estado-pill 
                                    {{ $empleado->estado == 'Activo' ? 'activo' : 'inactivo' }}">
                                    ● {{ $empleado->estado }}
                                </span>
                            </td>

                            {{-- ⚙️ ACCIONES --}}
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    <button class="btn btn-sm btn-light accion-btn"
                                            onclick="editarEmpleado({{ $empleado }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar">
                                        ✏
                                    </button>

                                    <button class="btn btn-sm btn-success accion-btn"
                                            onclick="abrirModalDias({{ $empleado->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDias">
                                        📅
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                @empty

                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay empleados registrados
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>