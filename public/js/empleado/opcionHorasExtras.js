/* =========================================================
   📌 OPCIONES (HORAS EXTRAS)
========================================================= */
function verOpciones(){

    let tipo = document.getElementById("estadoDia").value
    let cantidadDias = fechasSeleccionadas.length

    if(tipo === 'trabajado' && cantidadDias === 1){

        // ✅ SOLO UN DÍA → permitir horas extras
        document.getElementById("opcionHoras").style.display = "block"

    }else{

        // ❌ MULTI DÍAS o no trabajado → ocultar
        document.getElementById("opcionHoras").style.display = "none"

        document.getElementById("switchHoras").checked = false
        document.getElementById("inputHoras").style.display = "none"
        document.querySelector("#inputHoras input").value = ''
    }
}

function mostrarInputHoras(){

    let cantidadDias = fechasSeleccionadas.length

    // 🔥 seguridad: solo 1 día puede usar horas
    if(cantidadDias > 1){
        document.getElementById("switchHoras").checked = false
        document.getElementById("inputHoras").style.display = "none"
        return
    }

    let activo = document.getElementById("switchHoras").checked

    document.getElementById("inputHoras").style.display =
        activo ? "block" : "none"
}