<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasDeduccionesEmpleado">


    <div class="offcanvas-header">

        <h5 class="fw-bold">
            Deducciones de 
            <span id="nombreEmpleadoDeduccion"></span>
        </h5>


        <button class="btn-close"
                data-bs-dismiss="offcanvas">
        </button>

    </div>



    <div class="offcanvas-body">


        <form id="formDeduccionesEmpleado"
              method="POST">

            @csrf


            <div id="listaDeduccionesEmpleado">


            </div>



            <button class="btn btn-success w-100 mt-3">

                Guardar cambios

            </button>


        </form>


    </div>


</div>