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
