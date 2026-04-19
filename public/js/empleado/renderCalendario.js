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
