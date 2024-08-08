@extends('layouts.app')

@section('content')
@can('crear-docente')







<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-chalkboard-teacher me-1"></i>Registrar docentes
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('docentes.index') }}" class="text-decoration-none text-primary">
              <i class="fas fa-chalkboard-teacher me-1"></i>Docentes
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-chalkboard-teacher me-1"></i>Registrar docentes
          </li>
        </ol>
      </nav>
    </div>
  </div>
  <div>

  @if($errors->any())
  <div class="alert alert-danger" id="success-alert">
            @foreach ($errors->all() as $error)
                  @if ($error === "validation.mimes")
                      Formato del archivo no valido
                  @endif

                  @if ($error === "validation.max.file")
                      Archivo excedio el peso permitido
                  @endif        
            @endforeach
  </div>
  @endif
  <div class="card custom-card">
  <form id="docenteForm" action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
    @csrf
    <div class="col-md-6">
      <label for="nombre" class="form-label"><i class="fas fa-user me-2"></i>Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
      <div class="invalid-feedback">
        Ingrese el nombre.
      </div>
    </div>
    
    <div class="col-md-6">
      <label for="apellido_p" class="form-label"><i class="fas fa-user me-2"></i>Apellido Paterno</label>
      <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
      <div class="invalid-feedback">
        Ingrese el apellido paterno.
      </div>
    </div>
    
    <div class="col-md-6">
      <label for="apellido_m" class="form-label"><i class="fas fa-user me-2"></i>Apellido Materno</label>
      <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
      <div class="invalid-feedback">
        Ingrese el apellido materno.
      </div>
    </div>

    <div class="col-md-6">
      <label for="curp" class="form-label"><i class="fas fa-id-card me-2"></i>Curp</label>
      <input type="text" class="form-control" id="curp" name="curp" required pattern="^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}$">
      <div class="invalid-feedback">
        Ingrese una CURP válida.
      </div>
    </div>

    <div class="col-md-6">
      <label for="rfc" class="form-label"><i class="fas fa-id-card me-2"></i>RFC</label>
      <input type="text" class="form-control" id="rfc" name="rfc" required pattern="^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$">
      <div class="invalid-feedback">
        Ingrese un RFC válido.
      </div>
      <div class="custom-error-message text-danger" id="rfc-error-message" style="display:none;">
        El RFC no coincide con la CURP.
      </div>
    </div>

    <div class="col-md-6">
      <label for="area" class="form-label"><i class="fas fa-building me-2"></i>Área</label>
      <input type="text" class="form-control" id="area" name="area" required>
      <div class="invalid-feedback">
        Ingrese el área.
      </div>
    </div>

    <div class="col-md-6">
      <label for="telefono" class="form-label"><i class="fas fa-phone me-2"></i>Teléfono</label>
      <input  class="form-control" type="tel" id="telefono" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
      <small>Formato: 123-456-7890</small>
      <div class="invalid-feedback">
        Ingrese el teléfono.
      </div>
    </div>

    <div class="col-md-6">
      <label for="foto" class="form-label"><i class="fas fa-camera me-2"></i>Foto</label>
      <input type="file" class="form-control" id="foto" name="foto" required>
      <div class="text-xs">
          Peso máximo 512 KBs
      </div>
      <div class="invalid-feedback">
        Suba una foto.
      </div>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>

<script>
// JavaScript para la validación del formulario
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity() || !validateRFC()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
          } else {
            form.classList.remove('was-validated');
          }
        }, false)
      })
  })()

  function validateRFC() {
    const curp = document.getElementById('curp').value;
    const rfc = document.getElementById('rfc').value;
    const errorMessage = document.getElementById('rfc-error-message');
    
    if (curp.substring(0, 10) !== rfc.substring(0, 10)) {
      errorMessage.style.display = 'block';
      return false;
    } else {
      errorMessage.style.display = 'none';
      return true;
    }
  }

  // JavaScript adicional para mostrar mensajes de éxito
  document.addEventListener("DOMContentLoaded", function () {
    window.setTimeout(function () {
      const successAlert = document.getElementById("success-alert");
      if (successAlert) successAlert.style.display = 'none';
    }, 3000);
  });
</script>
@endcan
@endsection
