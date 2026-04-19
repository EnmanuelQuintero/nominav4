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