document.addEventListener("DOMContentLoaded", function(){

    const select = document.getElementById("selectNomina");

    if(select){
        select.addEventListener("change", function(){

            let id = this.value

            if(!id) return

            fetch(`/reportes/nomina/${id}`)
                .then(res => res.json())
                .then(data => renderEmpleados(data))
        })
    }

});

function renderEmpleados(data){

    let cont = document.getElementById("listaEmpleados")
    cont.innerHTML = ''

    Object.keys(data).forEach(area => {

        let html = `
            <div class="mb-3">

                <div class="fw-bold mb-2">
                    <input type="checkbox" onclick="toggleArea(this)">
                    ${area}
                </div>
        `

        data[area].forEach(emp => {
            html += `
                <div class="form-check ms-3">
                    <input class="form-check-input empleado-check"
                           type="checkbox"
                           value="${emp.id}">

                    <label class="form-check-label">
                        ${emp.nombre} (${emp.cargo})
                    </label>
                </div>
            `
        })

        html += `</div>`

        cont.innerHTML += html
    })
}

function seleccionarTodos(){
    document.querySelectorAll(".empleado-check")
        .forEach(e => e.checked = true)
}

function limpiarSeleccion(){
    document.querySelectorAll(".empleado-check")
        .forEach(e => e.checked = false)
}

function toggleArea(el){
    let container = el.closest('.mb-3')
    container.querySelectorAll(".empleado-check")
        .forEach(e => e.checked = el.checked)
}

function generarSolvencias(){

    let ids = []

    document.querySelectorAll(".empleado-check:checked")
        .forEach(e => ids.push(e.value))

    if(ids.length === 0){
        return alert("Seleccione al menos un empleado")
    }

    fetch('/reportes/solvencias', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ empleados: ids })
    })
    .then(res => {
        if(!res.ok){
            throw new Error("Error en la petición")
        }
        return res.blob()
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob)
        const a = document.createElement('a')

        a.href = url
        a.download = 'solvencias.pdf'
        a.click()

    })
    .catch(err => {
        console.error(err)
        alert("Error generando el PDF")
    })
}