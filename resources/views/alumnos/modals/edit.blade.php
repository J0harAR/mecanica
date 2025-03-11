@can('editar-alumnos')
                                            <!-- Modal de edicion -->
                                            <div class="modal fade" id="updateModal-{{ $alumno->no_control }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                                            <h5 class="modal-title" id="exampleModalLabel"><i
                                                                    class="bi bi-person-plus me-2"></i>Editar alumno</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="row g-3"
                                                                action="{{ route('alumnos.update', ['id' => $alumno->no_control]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')

                                                                <div class="col-md-12 mb-3">
                                                                    <label for="no_control" class="form-label"><i
                                                                            class="bi bi-card-text me-2"></i>Número de Control</label>
                                                                    <input type="text" class="form-control" id="no_control"
                                                                        name="no_control" value="{{ $alumno->no_control }}" required>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="nombre" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Nombre</label>
                                                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                                                        value="{{ $alumno->persona->nombre }}" required>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="apellido_p" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Apellido Paterno</label>
                                                                    <input type="text" class="form-control" id="apellido_p"
                                                                        name="apellido_p" value="{{ $alumno->persona->apellido_p }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="apellido_m" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Apellido Materno</label>
                                                                    <input type="text" class="form-control" id="apellido_m"
                                                                        name="apellido_m" value="{{ $alumno->persona->apellido_m }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="curp" class="form-label"><i
                                                                            class="bi bi-card-list me-2"></i>CURP</label>
                                                                    <input type="text" class="form-control" id="curp" name="curp"
                                                                        value="{{ $alumno->persona->curp }}" required pattern="^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}$">
                                                                </div>

                                                                <div class="text-center mt-4">
                                                                    <button type="submit" class="btn btn-primary"
                                                                        style="background-color: #002855; border-color: #002855;">Guardar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan