<div class="modal fade" id="crearDocenteModal" tabindex="-1" aria-labelledby="crearDocenteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearDocenteModalLabel">Añadir Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
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
        </div>
    </div>
</div>


<!-- Validaciones del rfc -->
@include('layouts.ValidacionRFC') 