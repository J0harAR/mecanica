@extends('layouts.app')

@section('content')

@can('ver-alumnos')


    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-user-graduate"></i> Alumnos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-user-graduate"></i>Alumnos
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group" role="group">
                @can('crear-alumnos')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="fas fa-plus-circle me-1"></i> Agregar Alumno
                    </button>
                @endcan

                @can('crear-alumnos')
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-asignar">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Asignar grupo
                    </button>

                @endcan

                @can('crear-alumnos')
                    <button type="button"class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-desasignar">
                        <i class="fas fa-trash-alt"></i> Desasignar alumno del grupo
                    </button>
                @endcan
            </div>



        </div>

        <!-- Vertically centered Modal -->
        <div class="modal fade" id="modal-asignar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                        <h5 class="modal-title"><i class="fas fa-chalkboard-teacher me-1"></i>Asignar grupo al alumno</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" action="{{ route('alumnos.asignar-grupo') }}" method="POST">
                            @csrf
                            <div class="col-md-12 mb-3">
                                <label for="no_control" class="form-label"><i class="bi bi-card-text me-2"></i>Número de
                                    Control</label>
                                <input type="text" class="form-control" id="no_control" name="no_control" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="grupos" class="form-label"><i class="bi bi-people me-2"></i>Grupo</label>
                                <select class="form-control" id="grupo" name="grupo" required>
                                    @foreach ($grupos as $grupo)
                                        <option value="{{ $grupo->clave_grupo }}">{{ $grupo->clave_grupo }}//{{$grupo->clave_periodo}}</option>
                                    @endforeach
                                </select>
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
        <!-- End Vertically centered Modal -->





        <!-- Vertically centered Modal -->
        <div class="modal fade" id="modal-desasignar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                        <h5 class="modal-title"> <i class="fas fa-trash-alt"></i> Desasignar grupo al alumno</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" action="{{ route('alumnos.desasignar-grupo') }}" method="POST">
                            @csrf
                            <div class="col-md-12 mb-3">
                                <label for="no_control" class="form-label"><i class="bi bi-card-text me-2"></i>Número de
                                    Control</label>
                                <input type="text" class="form-control" id="no_control" name="no_control" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="grupos" class="form-label"><i class="bi bi-people me-2"></i>Grupo</label>
                                <select class="form-control" id="grupo" name="grupo" required>
                                    @foreach ($grupos as $grupo)
                                        <option value="{{ $grupo->clave_grupo }}">{{ $grupo->clave_grupo }}//{{$grupo->clave_periodo}}</option>
                                    @endforeach
                                </select>
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
        <!-- End Vertically centered Modal -->











        @can('crear-alumnos')
            <!-- Vertically centered Modal -->
            <div class="modal fade" id="modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                            <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Agregar Alumno</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="{{ route('alumnos.store') }}" method="POST">
                                @csrf
                                <div class="col-md-12 mb-3">
                                    <label for="no_control" class="form-label"><i class="bi bi-card-text me-2"></i>Número de
                                        Control</label>
                                    <input type="text" class="form-control" id="no_control" name="no_control" required pattern="^\d{8}$">
                                    <div class="invalid-feedback">
                                        Ingrese un numero de control valido.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label"><i class="bi bi-person me-2"></i>Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido_p" class="form-label"><i class="bi bi-person me-2"></i>Apellido
                                        Paterno</label>
                                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido_m" class="form-label"><i class="bi bi-person me-2"></i>Apellido
                                        Materno</label>
                                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="curp" class="form-label"><i class="bi bi-card-list me-2"></i>CURP</label>
                                    <input type="text" class="form-control" id="curp" name="curp" required pattern="^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}$">
                                    <div class="invalid-feedback">
                                        Ingrese una CURP válida.
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="grupos" class="form-label"><i class="bi bi-people me-2"></i>Grupo</label>
                                    <select multiple class="form-control" id="grupos" name="grupos[]" required>
                                        @foreach ($grupos as $grupo)
                                            <option value="{{ $grupo->clave_grupo }}">{{ $grupo->clave_grupo }}//{{$grupo->clave_periodo}}</option>
                                        @endforeach
                                    </select>
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
            <!-- End Vertically centered Modal -->
        @endcan

        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const errorAlert = document.getElementById("success-alert");
                if (errorAlert) {
                    setTimeout(function () {
                        errorAlert.style.display = 'none';
                    }, 4000); 
                }
            });
        </script>

        @if (session('error'))
            <div class="alert alert-danger" id="error-alert">
                {{ session('error') }}
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    window.setTimeout(function () {
                        const successAlert = document.getElementById("error-alert");
                        if (successAlert) successAlert.style.display = 'none';
                    }, 3000);
                });
            </script>
        @endif


        @error('curp')
            <div class="alert alert-danger"id="error-alert">CURP duplicada</div>
        @enderror


        @error('no_control')
            <div class="alert alert-danger" id="error-alert">Numero de control duplicado</div>
        @enderror

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const errorAlert = document.getElementById("error-alert");
                if (errorAlert) {
                    setTimeout(function () {
                        errorAlert.style.display = 'none';
                    }, 4000); 
                }
            });
        </script>


        <form action="{{route('alumnos.filtrar-grupos')}}" method="POST" class="border p-4 rounded shadow-sm mb-5">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="periodo" class="form-label">Periodo</label>

                    <select name="periodo" id="periodo" class="form-control" required>
                        <option value=""disabled selected>Seleccione el periodo</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{$periodo->clave}}">{{$periodo->clave}}</option>

                        @endforeach
                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label for="grupos" class="form-label"><i class="bi bi-people me-2"></i>Grupo</label>
                    <select  class="form-control" id="grupo" name="grupo" required>
                        <option value=""disabled selected>Seleccione el grupo</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->clave_grupo }}">{{ $grupo->clave_grupo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary me-2">Filtrar</button>

            </div>

        </form>

        <!-- Tabla de alumnos -->
        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-body p-4">
                <div class="table-responsive">
                    @if (session('alumnos'))
                        <table class="table table-hover">
                            @if (session('grupo'))
                                <h5>Grupo:{{session('grupo')->clave_grupo}}</h5>
                            @endif

                            <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle"
                                style="border-collapse: separate; border-spacing: 0 10px;">
                                <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th>Número de Control</th>
                                        <th>Nombre</th>
                                        <th>Apellido Paterno</th>
                                        <th>Apellido Materno</th>
                                        <th>CURP</th>
                                        <th>Acciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('alumnos') as $alumno)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $alumno->no_control }}</td>
                                            <td>{{ $alumno->persona->nombre }}</td>
                                            <td>{{ $alumno->persona->apellido_p }}</td>
                                            <td>{{ $alumno->persona->apellido_m }}</td>
                                            <td>{{ $alumno->persona->curp }}</td>
                                            <td>
                                                @can('editar-alumnos')
                                                    <button type="button" class="btn btn-outline-primary btn-sm   "
                                                        data-bs-toggle="modal" data-bs-target="#updateModal-{{ $alumno->no_control }}">
                                                        <i class="fas fa-pen me-1"></i>
                                                    </button>
                                                @endcan

                                                @can('borrar-alumnos')
                                                    <button type="button" class="btn btn-outline-danger btn-sm   "
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $alumno->no_control }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan

                                            </td>
                                        </tr>

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
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="nombre" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Nombre</label>
                                                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                                                        value="{{ $alumno->persona->nombre }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="apellido_p" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Apellido Paterno</label>
                                                                    <input type="text" class="form-control" id="apellido_p"
                                                                        name="apellido_p" value="{{ $alumno->persona->apellido_p }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="apellido_m" class="form-label"><i
                                                                            class="bi bi-person me-2"></i>Apellido Materno</label>
                                                                    <input type="text" class="form-control" id="apellido_m"
                                                                        name="apellido_m" value="{{ $alumno->persona->apellido_m }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="curp" class="form-label"><i
                                                                            class="bi bi-card-list me-2"></i>CURP</label>
                                                                    <input type="text" class="form-control" id="curp" name="curp"
                                                                        value="{{ $alumno->persona->curp }}" required>
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

                                        @can('borrar-alumnos')
                                            <!-- Modal de eliminacion -->
                                            <div class="modal fade" id="deleteModal-{{ $alumno->no_control }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de que deseas eliminar el alumno
                                                            "{{ $alumno->no_control }}"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('alumnos.destroy', ['id' => $alumno->no_control]) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    @endforeach
                                </tbody>
                            </table>
                    @endif
                </div>
            </div>
            <!-- End Tabla de alumnos -->
        </div>
    </div>
@endcan
@endsection