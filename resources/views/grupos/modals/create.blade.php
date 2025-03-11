<div class="modal fade" id="creargrupoModal" tabindex="-1" aria-labelledby="creargrupoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="creargrupoModalLabel">Registrar grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
            <form class="row g-3 needs-validation" action="{{ route('grupos.store') }}" method="POST" novalidate>
                @csrf
                <div class="col-md-4">
                    <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i> Periodo</label>
                    <select class="form-select" id="floatingSelect" name="periodo" required>
                        <option selected disabled>Selecciona el periodo</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->clave }}">{{ $periodo->clave }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Seleccione un periodo
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i> Asignatura</label>
                    <select class="form-select" id="floatingSelect" name="asignatura" required>
                        <option selected disabled>Selecciona una asignatura</option>
                        @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->clave }}">{{ $asignatura->clave }} - {{ $asignatura->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Seleccione una asignatura.
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="clave_grupo" class="form-label"><i class="fas fa-users me-2"></i> Grupo</label>
                    <input type="text" class="form-control" id="clave_grupo" name="clave_grupo" required>
                    <div class="invalid-feedback">
                        Ingrese la clave del grupo.
                    </div>
                </div>
                
                <div class="col-12 d-flex justify-content-end mt-4">
                    @can('ver-grupos')                                       
                    <a href="{{ route('grupos.index') }}" class="btn btn-light btn-sm text-black me-2">
                        <i class="fas fa-arrow-left"></i> Atrás
                    </a>
                    @endcan
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-check"></i> Guardar
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>