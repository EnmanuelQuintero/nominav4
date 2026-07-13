<form action="{{ route('empleados.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="modal fade" id="modalNuevoEmpleado" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Nuevo Empleado</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center">

{{-- FOTO --}}
<img id="previewNuevoEmpleado"
     src="https://i.pravatar.cc/120"
     class="rounded-circle mb-3 d-block mx-auto"
     width="110"
     height="110"
     style="cursor:pointer; object-fit:cover;"
     onclick="document.getElementById('inputFotoEmpleado').click()">

<small class="text-muted d-block mb-3">
Haga click en la imagen para agregar foto
</small>

<input type="file"
       name="foto"
       id="inputFotoEmpleado"
       accept="image/*"
       style="display:none"
       onchange="previewFotoEmpleado(event)">

<div class="row">

{{-- Nombre --}}
<div class="col-12 mb-3 text-start">
<label class="form-label">Nombre Completo</label>
<input type="text" name="nombre" class="form-control" required>
</div>

{{-- NUMERO EMPLEADO --}}
<div class="col-md-6 mb-3 text-start">
    <label class="form-label">Número de empleado</label>
    <input type="text" name="numero_empleado" class="form-control" required>
</div>


{{-- Cedula --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Cedula</label>
<input type="text" name="cedula" class="form-control" required>
</div>

{{-- Correo --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Correo</label>
<input type="email" name="correo" class="form-control">
</div>


{{-- Telefono --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Teléfono</label>
<input type="text" name="telefono" class="form-control">
</div>

{{-- Área --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Área</label>

<select name="area_id"
        id="areaSelect"
        class="form-control"
        onchange="filtrarCargos()">

<option value="">Seleccione un área</option>

@foreach($areas as $area)
<option value="{{ $area->id }}">{{ $area->nombre }}</option>
@endforeach

</select>

</div>

{{-- Cargo --}}
<div class="col-md-6 mb-3 text-start">

<label class="form-label">Cargo</label>

<select name="cargo_id"
        id="cargoSelect"
        class="form-control"
        disabled>

<option value="">Seleccione un cargo</option>

@foreach($cargos as $cargo)
<option value="{{ $cargo->id }}"
        data-area="{{ $cargo->area_id }}">
{{ $cargo->nombre }}
</option>
@endforeach

</select>

</div>

{{-- INSS --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">INSS</label>
<input type="text" name="inss" class="form-control">
</div>

{{-- Cuenta bancaria --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Cuenta Bancaria</label>
<input type="text" name="cuenta_bancaria" class="form-control">
</div>

{{-- Salario --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Salario</label>
<input type="number" name="salario" class="form-control" required>
</div>

{{-- Fecha inicio --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Fecha de inicio</label>
<input type="date" name="fecha_inicio" class="form-control" required>
</div>

{{-- ESTADO --}}
<div class="col-md-6 mb-3 text-start">
<label class="form-label">Estado</label>

<select name="estado"
        id="estadoNuevo"
        class="form-control"
        onchange="toggleFechaFinNuevo()">

<option value="Activo">Activo</option>
<option value="Subsidio">Subsidio</option>
<option value="Renuncia">Renuncia</option>
<option value="Despedido">Despedido</option>

</select>
</div>

{{-- FECHA FINAL --}}
<div class="col-md-6 mb-3 text-start d-none" id="campoFechaFinNuevo">
<label class="form-label">Fecha de Finalización</label>
<input type="date"
       name="fecha_finalizacion"
       id="fechaFinNuevo"
       class="form-control">
</div>

</div>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">
Cancelar
</button>

<button type="submit" class="btn btn-success">
Crear empleado
</button>
</div>

</div>
</div>
</div>

</form>