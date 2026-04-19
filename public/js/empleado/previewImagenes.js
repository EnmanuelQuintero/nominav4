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
