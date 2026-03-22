<div id="vista-cards" style="display:none;">

    @forelse($empleados->groupBy('cargo.area.nombre') as $area => $grupo)

        {{-- TITULO DEL AREA --}}
        <div class="mb-4 mt-4">
            <h5 class="fw-bold border-bottom pb-2 text-primary">
                {{ $area ?? 'Sin área' }}
            </h5>
        </div>

        <div class="row g-4">

            @foreach($grupo as $empleado)

                <div class="col-md-3">

                    <div class="card card-empleado-pro border-0 shadow-sm"
                         onclick="editarEmpleado({{ $empleado }})"
                         data-bs-toggle="modal"
                         data-bs-target="#modalEditar"
                         style="cursor:pointer;">

                        <div class="card-body d-flex flex-column text-center position-relative">

                            {{-- FOTO + ESTADO --}}
                            <div class="mb-3 d-flex justify-content-center">

                                <div class="contenedor-foto">

                                    <img src="{{ $empleado->foto 
                                        ? asset('storage/'.$empleado->foto) 
                                        : 'https://i.pravatar.cc/100' }}"
                                        class="img-empleado">

                                    {{-- INDICADOR --}}
                                    <span class="estado-dot 
                                        {{ $empleado->estado == 'Activo' ? 'activo' : 'inactivo' }}">
                                    </span>

                                </div>

                            </div>

                            {{-- NOMBRE --}}
                            <h6 class="fw-bold mb-1 text-truncate">
                                {{ $empleado->nombre }}
                            </h6>

                            {{-- CARGO --}}
                            <p class="text-muted small mb-2">
                                {{ $empleado->cargo->nombre ?? '' }}
                            </p>

                            {{-- SALARIO --}}
                            <p class="fw-bold text-success mb-2">
                                C$ {{ number_format($empleado->salario, 2) }}
                            </p>


                            {{-- ESTADO TEXTO --}}
                            <small class="mb-3 
                                {{ $empleado->estado == 'Activo' ? 'text-success' : 'text-danger' }}">
                                ● {{ $empleado->estado }}
                            </small>

                            {{-- BOTONES --}}
                            <div class="mt-auto d-flex gap-2">

                                <button class="btn btn-light btn-sm w-50 rounded-pill shadow-sm"
                                        onclick="event.stopPropagation(); editarEmpleado({{ $empleado }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar">
                                    ✏
                                </button>

                                <button class="btn btn-success btn-sm w-50 rounded-pill shadow-sm"
                                        onclick="event.stopPropagation(); abrirModalDias({{ $empleado->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDias">
                                    📅
                                </button>

                            </div>

                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    @empty

        <div class="text-center text-muted py-5">
            No hay empleados registrados
        </div>

    @endforelse

</div>