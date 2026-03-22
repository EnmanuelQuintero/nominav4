@extends('layouts.app')

@section('title', 'Empleados')

@section('content')

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


<button 
    class="btn btn-primary rounded-circle shadow position-fixed"
    style="bottom: 25px; right: 25px; width:60px; height:60px; font-size:28px; z-index:1050;"
    data-bs-toggle="modal"
    data-bs-target="#modalNuevoEmpleado">

    +

</button>


{{-- Script simple para cambiar vista --}}
<script>

/* =========================================================
   📌 VISTAS (TABLA / CARDS)
========================================================= */
function mostrarTabla() {
    document.getElementById('vista-tabla').style.display = 'block';
    document.getElementById('vista-cards').style.display = 'none';
}

function mostrarCards() {
    document.getElementById('vista-tabla').style.display = 'none';
    document.getElementById('vista-cards').style.display = 'block';
}


/* =========================================================
   📌 IMÁGENES (PREVIEW)
========================================================= */
function previewImagen(input, targetId){
    const file = input.files[0];

    if(file){
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(targetId).src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Reutilizamos función
function cambiarImagen(e){ previewImagen(e.target, 'previewImagen') }
function previewFotoEmpleado(e){ previewImagen(e.target, 'previewNuevoEmpleado') }


/* =========================================================
   📌 VARIABLES GLOBALES (CALENDARIO)
========================================================= */
let empleadoId = null
let fechaSeleccionada = null
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
   📌 CALENDARIO (RENDER)
========================================================= */
function renderCalendario(){

    let calendario = document.getElementById("calendario")
    calendario.innerHTML = ""

    let year = fechaActual.getFullYear()
    let month = fechaActual.getMonth()

    let primerDia = new Date(year, month, 1)
    let ultimoDia = new Date(year, month + 1, 0)

    document.getElementById("tituloCalendario").innerText =
        primerDia.toLocaleString('es-ES', { month: 'long', year: 'numeric' })

    let inicio = primerDia.getDay()
    if(inicio === 0) inicio = 7

    // espacios vacíos
    for(let i = 1; i < inicio; i++){
        calendario.innerHTML += `<div></div>`
    }

    // días
    for(let d = 1; d <= ultimoDia.getDate(); d++){

        let fecha = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`

        let data = diasData[fecha] || {}
        let estado = data.tipo || ''
        let horas = data?.horas_extras?.cantidad_horas || null

        // 🎨 CLASES
        let clases = estado || ""

        // 🎯 ICONOS
        const iconos = {
            trabajado: "✔",
            vacaciones: "🌴",
            compensado: "↺",
            no_trabajado: "✖"
        }

        let icono = iconos[estado] || ""

        // 💬 TOOLTIP
        const textos = {
            trabajado: "🟢 Trabajado",
            vacaciones: "🟡 Vacaciones",
            compensado: "🔵 Compensado",
            no_trabajado: "🔴 No trabajado"
        }

        let tooltip = textos[estado] || "Sin registro"
        if(horas) tooltip += ` | ${horas}h extras`

        calendario.innerHTML += `
            <div class="dia ${clases}"
                onclick="seleccionarDia('${fecha}', this)"
                data-bs-toggle="tooltip"
                title="${tooltip}">

                <div class="numero">${d}</div>

                ${icono ? `<div class="icono">${icono}</div>` : ''}

                ${horas ? `<div class="horas">+${horas}h</div>` : ''}

            </div>
        `
    }

    activarTooltips()
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

    fechaSeleccionada = fecha

    document.querySelectorAll("#calendario .dia")
        .forEach(el => el.classList.remove("seleccionado"))

    elemento.classList.add("seleccionado")

    const select = document.getElementById("estadoDia")
    select.disabled = false

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
}


/* =========================================================
   📌 OPCIONES (HORAS EXTRAS)
========================================================= */
function verOpciones(){

    let tipo = document.getElementById("estadoDia").value

    if(tipo === 'trabajado'){
        document.getElementById("opcionHoras").style.display = "block"
    }else{
        document.getElementById("opcionHoras").style.display = "none"
        document.getElementById("switchHoras").checked = false
        document.getElementById("inputHoras").style.display = "none"
    }
}

function mostrarInputHoras(){
    let activo = document.getElementById("switchHoras").checked
    document.getElementById("inputHoras").style.display =
        activo ? "block" : "none"
}


/* =========================================================
   📌 GUARDAR DÍA
========================================================= */
function guardarDia(){

    if(!fechaSeleccionada){
        return alert("Seleccione un día")
    }

    let tipo = document.getElementById("estadoDia").value
    if(!tipo){
        return alert("Seleccione un estado")
    }

    let horas = document.getElementById("switchHoras").checked
        ? document.querySelector("#inputHoras input").value
        : null

    fetch('/empleados/dias', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            empleado_id: empleadoId,
            fecha: fechaSeleccionada,
            tipo,
            horas
        })
    })
    .then(res => res.json())
    .then(() => cargarDatos())
}


/* =========================================================
   📌 FILTRAR CARGOS POR ÁREA
========================================================= */
function filtrarCargos(){

    let area = document.getElementById("areaSelect").value
    let cargoSelect = document.getElementById("cargoSelect")

    cargoSelect.value = ""

    if(!area){
        cargoSelect.disabled = true
        return
    }

    cargoSelect.disabled = false

    cargoSelect.querySelectorAll("option").forEach(cargo => {
        if(!cargo.value) return
        cargo.style.display = (cargo.dataset.area == area) ? "block" : "none"
    })
}


/* =========================================================
   📌 EDITAR EMPLEADO (CARGAR DATOS)
========================================================= */
function editarEmpleado(empleado){
    document.getElementById('formEditarEmpleado').reset()
    // ACTION FORM
    document.getElementById('formEditarEmpleado').action =
        `/empleados/${empleado.id}`

    // DATOS
    document.getElementById('editNumero').value = empleado.numero_empleado ?? ''
    document.getElementById('editCorreo').value = empleado.correo ?? ''
    document.getElementById('editTelefono').value = empleado.telefono ?? ''
    document.getElementById('editNombre').value = empleado.nombre
    document.getElementById('editCedula').value = empleado.cedula
    document.getElementById('editInss').value = empleado.inss ?? ''
    document.getElementById('editCuenta').value = empleado.cuenta_bancaria ?? ''
    document.getElementById('editSalario').value = empleado.salario
    document.getElementById('editFechaInicio').value =
    empleado.fecha_inicio
        ? empleado.fecha_inicio.split('T')[0]
        : ''
    document.getElementById('editEstado').value = empleado.estado
    document.getElementById('editCargo').value = empleado.cargo_id

    // FECHA FINAL
document.getElementById('editFechaFin').value =
    empleado.fecha_finalizacion
        ? empleado.fecha_finalizacion.split('T')[0]
        : ''
    // 🔥 ACTIVAR / OCULTAR CAMPO
    toggleFechaFin()

    // IMAGEN
    document.getElementById('previewImagen').src =
        empleado.foto
        ? `/storage/${empleado.foto}`
        : 'https://i.pravatar.cc/100'
}

/* =========================================================
   📌 CONTROL FECHA FINAL SEGÚN ESTADO
========================================================= */
function toggleFechaFin(){

    let estado = document.getElementById('editEstado').value
    let campo = document.getElementById('campoFechaFin')

    if(estado === 'Renuncia' || estado === 'Despedido'){
        campo.classList.remove('d-none')
    }else{
        campo.classList.add('d-none')
        document.getElementById('editFechaFin').value = ''
    }

}

</script>
@endsection