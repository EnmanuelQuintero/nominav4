

// MOSTRAR U OCULTAR FILTROS

document
.getElementById('tipoReporte')
.addEventListener('change', function () {

    document
        .getElementById('contenedorArea')
        .classList.add('d-none');

    document
        .getElementById('contenedorEmpleado')
        .classList.add('d-none');

    if(this.value === 'area')
    {
        document
            .getElementById('contenedorArea')
            .classList.remove('d-none');
    }

    if(this.value === 'empleado')
    {
        document
            .getElementById('contenedorEmpleado')
            .classList.remove('d-none');
    }

});


// FILTRAR NOMINAS POR AÑO Y MES

function filtrarNominas()
{
    let anio = document.getElementById('anio').value;
    let mes = document.getElementById('mes').value;

    let opciones =
        document.querySelectorAll('#selectNomina option');

    opciones.forEach(op => {

        if(!op.value) return;

        let visible =
            op.dataset.anio == anio &&
            op.dataset.mes == mes;

        op.hidden = !visible;

    });

    document.getElementById('selectNomina').value = '';
}

document
.getElementById('anio')
.addEventListener('change', filtrarNominas);

document
.getElementById('mes')
.addEventListener('change', filtrarNominas);

document
.getElementById('selectNomina')
.addEventListener('change', function () {

    let nominaId = this.value;

    if(!nominaId)
    {
        return;
    }

    fetch(`/reportes/nomina/${nominaId}/filtros`)
    .then(response => response.json())
    .then(data => {

        cargarAreas(data.areas);

        cargarEmpleados(data.empleados);

    });

});


function cargarAreas(areas)
{
    let select =
        document.getElementById('area');

    select.innerHTML =
        '<option value="">Seleccione área</option>';

    areas.forEach(area => {

        select.innerHTML += `
            <option value="${area}">
                ${area}
            </option>
        `;

    });
}


function cargarEmpleados(empleados)
{
    let contenedor =
        document.getElementById('empleado');

    contenedor.innerHTML = '';

    empleados.forEach(emp => {

        contenedor.innerHTML += `

            <div class="form-check mb-2">

                <input 
                    class="form-check-input empleado-check"
                    type="checkbox"
                    value="${emp.empleado_id}"
                    id="emp_${emp.empleado_id}"
                >

                <label 
                    class="form-check-label"
                    for="emp_${emp.empleado_id}">
                    
                    ${emp.numero} - ${emp.nombre}
                    <small class="text-muted">
                        (${emp.cargo})
                    </small>

                </label>

            </div>

        `;

    });
}

function generarSolvencias()
{
    let nomina_id =
        document.getElementById('selectNomina').value;

    let tipo =
        document.getElementById('tipoReporte').value;

    let area =
        document.getElementById('area').value;

    let empleados = [];

    document
    .querySelectorAll('.empleado-check:checked')
    .forEach(chk => {

        empleados.push(chk.value);

    });

    if(!nomina_id)
    {
        alert('Seleccione una nómina');
        return;
    }

    let token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    let form = document.createElement('form');

    form.method = 'POST';
    form.action = '/reportes/solvencias';


    form.innerHTML = `
        <input type="hidden" name="_token" value="${token}">
        <input type="hidden" name="nomina_id" value="${nomina_id}">
        <input type="hidden" name="tipo" value="${tipo}">
        <input type="hidden" name="area" value="${area}">
        ${empleados.map(id => 
        `
        <input 
        type="hidden" 
        name="empleados[]" 
        value="${id}">
        `
        ).join('')}
    `;

    document.body.appendChild(form);

    form.submit();

    document.body.removeChild(form);
}
