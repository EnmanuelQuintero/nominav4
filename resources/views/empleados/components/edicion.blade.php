<form id="formEditarEmpleado"
      method="POST"
      enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="modal fade" id="modalEditar" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Editar Empleado</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center">

{{-- IMAGEN --}}
<img id="previewImagen"
     src=""
     class="rounded-circle mb-3 d-block mx-auto"
     width="110"
     height="110"
     style="cursor:pointer; object-fit:cover;"
     onclick="document.getElementById('inputImagen').click()">

<input type="file"
       name="foto"
       id="inputImagen"
       accept="image/*"
       style="display:none"
       onchange="cambiarImagen(event)">

<div class="row">

{{-- NUMERO EMPLEADO --}}
<div class="col-md-6 mb-3 text-start">
<label>N° Empleado</label>
<input type="text" name="numero_empleado" id="editNumero" class="form-control">
</div>

{{-- NOMBRE --}}
<div class="col-md-6 mb-3 text-start">
<label>Nombre</label>
<input type="text" name="nombre" id="editNombre" class="form-control">
</div>

{{-- CEDULA --}}
<div class="col-md-6 mb-3 text-start">
<label>Cédula</label>
<input type="text" name="cedula" id="editCedula" class="form-control">
</div>

{{-- CORREO --}}
<div class="col-md-6 mb-3 text-start">
<label>Correo</label>
<input type="email" name="correo" id="editCorreo" class="form-control">
</div>

{{-- TELEFONO --}}
<div class="col-md-6 mb-3 text-start">
<label>Teléfono</label>
<input type="text" name="telefono" id="editTelefono" class="form-control">
</div>

{{-- INSS --}}
<div class="col-md-6 mb-3 text-start">
<label>INSS</label>
<input type="text" name="inss" id="editInss" class="form-control">
</div>

{{-- CUENTA --}}
<div class="col-md-6 mb-3 text-start">
<label>Cuenta Bancaria</label>
<input type="text" name="cuenta_bancaria" id="editCuenta" class="form-control">
</div>

{{-- CARGO --}}
<div class="col-md-6 mb-3 text-start">
<label>Cargo</label>

<select name="cargo_id" id="editCargo" class="form-control">
@foreach($cargos as $cargo)
<option value="{{ $cargo->id }}">
{{ $cargo->nombre }} - {{ $cargo->area->nombre }}
</option>
@endforeach
</select>

</div>

{{-- SALARIO --}}
<div class="col-md-6 mb-3 text-start">
<label>Salario</label>
<input type="number" name="salario" id="editSalario" class="form-control">
</div>

{{-- FECHA INICIO --}}
<div class="col-md-6 mb-3 text-start">
<label>Fecha inicio</label>
<input type="date" name="fecha_inicio" id="editFechaInicio" class="form-control">
</div>

{{-- ESTADO --}}
<div class="col-md-6 mb-3 text-start">
<label>Estado</label>

<select name="estado"
        id="editEstado"
        class="form-control"
        onchange="toggleFechaFin()">

<option value="Activo">Activo</option>
<option value="Subsidio">Subsidio</option>
<option value="Renuncia">Renuncia</option>
<option value="Despedido">Despedido</option>

</select>

</div>

{{-- FECHA FINAL --}}
<div class="col-md-6 mb-3 text-start d-none" id="campoFechaFin">
<label>Fecha Finalización</label>
<input type="date" name="fecha_finalizacion" id="editFechaFin" class="form-control">
</div>

</div>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-success">Guardar</button>
</div>

</div>
</div>
</div>

</form>