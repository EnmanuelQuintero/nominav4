@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold">
                Deducciones
            </h4>

            <small class="text-muted">
                Administración de deducciones aplicables a empleados
            </small>
        </div>


        <button class="btn btn-primary rounded-pill"
                onclick="nuevaDeduccion()"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDeduccion">

            + Nueva Deducción

        </button>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif



    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th width="150">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>


                @foreach($deducciones as $d)

                    <tr>


                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>
                            {{ $d->nombre }}
                        </td>


                        <td>

                            @if($d->tipo == 'monto')

                                <span class="badge bg-primary">
                                    Monto fijo
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Porcentaje
                                </span>

                            @endif

                        </td>



                        <td>

                            @if($d->tipo == 'monto')

                                C$ {{ number_format($d->valor,2) }}

                            @else

                                {{ $d->valor }} %

                            @endif

                        </td>



                        <td>

                            @if($d->activa)

                                <span class="badge bg-success">
                                    Activa
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactiva
                                </span>

                            @endif

                        </td>



                        <td>


                            <button type="button"
                                    class="btn btn-sm btn-warning"
                                    onclick='editarDeduccion(@json($d))'
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasDeduccion">

                                <i class="bi bi-pencil"></i>
                                Editar

                            </button>



                            <form action="{{ route('deducciones.destroy',$d) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')


                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar deducción?')">

                                    <i class="bi bi-trash"></i>

                                </button>


                            </form>


                        </td>


                    </tr>


                @endforeach


                </tbody>

            </table>


        </div>

    </div>


</div>


@include('deducciones.components.agregar')


@endsection



@push('scripts')

<script>


// Cambiar etiqueta según tipo

document.addEventListener('DOMContentLoaded', function(){


document.getElementById('tipo')
.addEventListener('change',function(){

    let label = document.getElementById('labelValor');


    if(this.value === 'porcentaje'){

        label.innerHTML = "Porcentaje (%)";

    }else{

        label.innerHTML = "Monto (C$)";

    }

});


});




// Editar deducción

function editarDeduccion(deduccion)
{

    console.log(deduccion);


    document.getElementById('tituloDeduccion').innerHTML =
        "Editar Deducción";


    document.getElementById('nombre').value =
        deduccion.nombre ?? '';



    document.getElementById('tipo').value =
        deduccion.tipo ?? '';



    document.getElementById('valor').value =
        deduccion.valor ?? '';



    document.getElementById('descripcion').value =
        deduccion.descripcion ?? '';



    let form = document.getElementById('formDeduccion');


    form.action =
        '/deducciones/' + deduccion.id;



    document.getElementById('methodDeduccion').value =
        'PUT';

}




// Nueva deducción

function nuevaDeduccion()
{

    let form = document.getElementById('formDeduccion');


    form.reset();



    document.getElementById('tituloDeduccion').innerHTML =
        "Nueva Deducción";



    form.action =
        '/deducciones';



    document.getElementById('methodDeduccion').value =
        'POST';


}



</script>

@endpush