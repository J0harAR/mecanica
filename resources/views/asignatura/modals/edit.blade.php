@can('editar-asignatura')
<!-- Modal de Editar -->
<div class="modal fade" id="editModal-{{$asignatura->clave}}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil me-2"></i> Editar Asignatura</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('asignatura.update', ['id' => $asignatura->clave]) }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    
                    <div class="col-md-12">
                        <label for="nombre" class="form-label"><i class="fas fa-book me-2"></i> Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ $asignatura->nombre }}" required>
                        @error('nombre')
                         <div class="invalid-feedback">
                         {{ $message }}
                          </div>
                             @else
                             <div class="invalid-feedback">
                                 Ingrese el nombre completo de la asignatura.
                              </div>
                          @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="clave" class="form-label"><i class="fas fa-key me-2"></i> Clave de Asignatura</label>
                        <input type="text" name="clave" id="clave" class="form-control" value="{{ $asignatura->clave }}" disabled>
                        <div class="invalid-feedback">
                            Ingrese la clave de la asignatura.
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary btn-sm shadow">
                            <i class="bi bi-check2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan