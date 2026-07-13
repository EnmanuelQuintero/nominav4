@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/empleado/tabla.css') }}">
    @endpush
<h3 class="mb-4">Listado de Empleados</h3>

{{-- Botones de vista --}}
<div class="mb-3">
    <button class="btn btn-outline-primary btn-sm" onclick="mostrarTabla()">
        Vista Tabla
    </button>

    <button class="btn btn-outline-secondary btn-sm" onclick="mostrarCards()">
        Vista Miniaturas
    </button>
</div>

{{-- ===================== --}}
{{-- VISTA TABLA --}}
{{-- ===================== --}}
@include("empleados.components.tabla")
{{-- ===================== --}}
{{-- VISTA CARDS --}}
{{-- ===================== --}}
@include("empleados.components.cuadro")
{{-- ===================== --}}
{{-- ACCIONES --}}
{{-- ===================== --}}
@include("empleados.components.edicion")
@include("empleados.components.diasTrabajados")
@include("empleados.components.nuevoEmpleado")

@include('empleados.components.deducciones')
<button 
    class="btn btn-primary rounded-circle shadow position-fixed"
    style="bottom: 25px; right: 25px; width:60px; height:60px; font-size:28px; z-index:1050;"
    data-bs-toggle="modal"
    data-bs-target="#modalNuevoEmpleado">

    +

</button>




<script>
    function abrirDeducciones(id,nombre)
{

    document.getElementById(
        'nombreEmpleadoDeduccion'
    ).innerHTML = nombre;


    fetch(`/empleados/${id}/deducciones`)
    .then(res=>res.json())
    .then(data=>{


        let html='';


        data.deducciones.forEach(d=>{


            let checked =
                data.asignadas.includes(d.id)
                ? 'checked'
                : '';



            html += `

            <div class="card mb-2">

                <div class="card-body">


                    <div class="form-check">

                        <input 
                        class="form-check-input"
                        type="checkbox"
                        name="deducciones[]"
                        value="${d.id}"
                        ${checked}>


                        <label class="form-check-label fw-bold">

                            ${d.nombre}

                        </label>


                    </div>


                    <small class="text-muted">

                    ${
                    d.tipo=='porcentaje'
                    ? d.valor+' %'
                    : 'C$ '+d.valor
                    }

                    </small>


                </div>

            </div>


            `;

        });



        document.getElementById(
            'listaDeduccionesEmpleado'
        ).innerHTML=html;



        document.getElementById(
            'formDeduccionesEmpleado'
        ).action =
        `/empleados/${id}/deducciones`;



        let elemento = document.getElementById(
            'offcanvasDeduccionesEmpleado'
        );

        let panel = new bootstrap.Offcanvas(elemento);

        panel.show();


    });

}
</script>

<script>


// Reutilizamos función
function cambiarImagen(e){ previewImagen(e.target, 'previewImagen') }
function previewFotoEmpleado(e){ previewImagen(e.target, 'previewNuevoEmpleado') }


/* =========================================================
   📌 VARIABLES GLOBALES (CALENDARIO)
========================================================= */
let empleadoId = null
let fechasSeleccionadas = []
let diasData = {}
let fechaActual = new Date()


/* =========================================================
   📌 MODAL DÍAS
========================================================= */
function abrirModalDias(id){
    empleadoId = id
    resetUI()
    cargarDatos()
}

// Reset visual
function resetUI(){
    fechaSeleccionada = null
    document.getElementById("estadoDia").value = ''
    document.getElementById("estadoDia").disabled = true
    document.getElementById("opcionHoras").style.display = "none"
}


/* =========================================================
   📌 CARGAR DATOS DESDE BACKEND
========================================================= */
function cargarDatos(){
    fetch(`/empleados/dias/${empleadoId}`)
    .then(res => res.json())
    .then(data => {
        diasData = {}

        data.forEach(d => {
            diasData[d.fecha] = d
        })

        renderCalendario()
    })
}


/* =========================================================
   📌 TOOLTIP (Bootstrap)
========================================================= */
function activarTooltips(){
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el)
    })
}




/* =========================================================
   📌 CAMBIO DE MES
========================================================= */
function cambiarMes(valor){
    fechaActual.setMonth(fechaActual.getMonth() + valor)
    renderCalendario()
}


/* =========================================================
   📌 SELECCIONAR DÍA
========================================================= */
function seleccionarDia(fecha, elemento){

    // Si ya está seleccionada → quitarla
    if(fechasSeleccionadas.includes(fecha)){
        fechasSeleccionadas = fechasSeleccionadas.filter(f => f !== fecha)
        elemento.classList.remove("seleccionado")
    }else{
        // Agregar
        fechasSeleccionadas.push(fecha)
        elemento.classList.add("seleccionado")
    }

    const select = document.getElementById("estadoDia")
    select.disabled = fechasSeleccionadas.length === 0

    // 🔥 SOLO SI ES UNA FECHA mostramos datos existentes
    if(fechasSeleccionadas.length === 1){

        let data = diasData[fecha] || {}

        select.value = data.tipo || ''
        verOpciones()

        const switchHoras = document.getElementById("switchHoras")
        const inputHorasDiv = document.getElementById("inputHoras")
        const inputHoras = document.querySelector("#inputHoras input")

        if(data.horas_extras && data.tipo === 'trabajado'){
            switchHoras.checked = true
            inputHorasDiv.style.display = "block"
            inputHoras.value = data.horas_extras.cantidad_horas
        }else{
            switchHoras.checked = false
            inputHorasDiv.style.display = "none"
            inputHoras.value = ''
        }

    }else{
        // 🔥 MULTI → limpiamos inputs
        select.value = ''
        document.getElementById("switchHoras").checked = false
        document.getElementById("inputHoras").style.display = "none"
        document.querySelector("#inputHoras input").value = ''
    }
}
/* =========================================================
   📌 GUARDAR DÍA
========================================================= */
function guardarDia(){

    if(fechasSeleccionadas.length === 0){
        return alert("Seleccione al menos un día")
    }

    let tipo = document.getElementById("estadoDia").value
    if(!tipo){
        return alert("Seleccione un estado")
    }

    let horas = document.getElementById("switchHoras").checked
        ? document.querySelector("#inputHoras input").value
        : null

    fetch('/empleados/dias', { // 🔥 AQUÍ EL CAMBIO
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            empleado_id: empleadoId,
            fechas: fechasSeleccionadas, // 👈 ahora manda array
            tipo,
            horas
        })
    })
    .then(res => res.json())
    .then(() => {
        fechasSeleccionadas = []

        document.querySelectorAll("#calendario .dia")
            .forEach(el => el.classList.remove("seleccionado"))

        cargarDatos()
    })
}

/* =========================================================
   📌 FILTRAR CARGOS POR ÁREA
========================================================= */
function filtrarCargos() {

    let area = document.getElementById("areaSelect").value;
    let cargoSelect = document.getElementById("cargoSelect");
    let opcionInicial = cargoSelect.querySelector("option[value='']");

    cargoSelect.value = "";

    if (!area) {
        cargoSelect.disabled = true;
        opcionInicial.textContent = "Seleccione un área primero";
        return;
    }

    let hayCargos = false;

    cargoSelect.querySelectorAll("option").forEach(cargo => {
        if (!cargo.value) return;

        if (cargo.dataset.area == area) {
            cargo.style.display = "";
            hayCargos = true;
        } else {
            cargo.style.display = "none";
        }
    });

    if (hayCargos) {
        cargoSelect.disabled = false;
        opcionInicial.textContent = "Seleccione un cargo";
    } else {
        cargoSelect.disabled = true;
        opcionInicial.textContent = "No hay cargos disponibles";
    }
}   


</script>
    @pushOnce('scripts')
        <script src="{{ asset('js/empleado/editEmpleado.js') }}"></script>
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ asset('js/empleado/opcionHorasExtras.js') }}"></script>
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ asset('js/empleado/renderCalendario.js') }}"></script>
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ asset('js/empleado/previewImagenes.js') }}"></script>
    @endPushOnce

    @pushOnce('scripts')
        <script src="{{ asset('js/empleado/switchVista.js') }}"></script>
    @endPushOnce
    
@endsection