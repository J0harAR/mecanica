@can('editar-periodo')
                                            
   <!-- Vertically centered Modal -->
<div class="modal fade" id="modal-{{$periodo->clave}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar periodo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('periodos.update', ['id' => $periodo->clave]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-12 mb-3">
                        <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>Clave del periodo</label>
                        <input type="text" class="form-control" name="periodo" id="periodo" value="{{$periodo->clave}}" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha de inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required value="{{$periodo->fecha_inicio}}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_final" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha final</label>
                            <input type="date" class="form-control" name="fecha_final" id="fecha_final"  required value="{{$periodo->fecha_final}}">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Vertically centered Modal -->
@endcan