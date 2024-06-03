@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-clipboard-list me-2"></i>Inventario
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('inventario.index') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-clipboard-list me-1"></i>Inventario
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-gear"></i>Maquinaria
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fas fa-plus-circle me-1"></i>Agregar maquinaria
            </button>
        </div>
    </div>

    <!-- Vertically centered Modal -->
    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Agregar maquinaria</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('maquinaria.store') }}" method="POST">
                        @csrf

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Nombre de la
                                maquinaria</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                autocomplete="nombre" autofocus>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label"><i class="bi bi-stack me-2"></i>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estatus" class="form-label"><i
                                    class="bi bi-check-circle me-2"></i>Estatus</label>
                            <select id="estatus" class="form-select" required name="estatus">
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="tipoMaquina">
                            <label for="tipo_maquina" class="form-label"><i class="bi bi-robot me-2"></i>Tipo de
                                máquina</label>
                            <input type="text" class="form-control" id="tipo_maquina" name="tipo_maquina" required>
                        </div>



                        <div class="col-md-6 mb-3" id="seccion">
                            <label for="seccion" class="form-label"><i class="bi bi-tags me-2"></i>Sección</label>
                            <select id="seccion" class="form-select" name="seccion">
                                <option selected disabled>Selecciona una sección</option>
                                <option value="03">03 Metrología II</option>
                                <option value="04">04 Mecánica de materiales</option>
                                <option value="05">05 Mantenimiento</option>
                                <option value="06">06 Robots industriales</option>
                                <option value="07">07 Mecánica de materiales</option>
                                <option value="08">08 Manufactura sustractiva</option>
                                <option value="09">09 Manufactura aditiva</option>
                                <option value="12">12 Mecánica de fluidos y termodinámica</option>
                                <option value="13">13 Neumática</option>
                                <option value="20">20 Área de diseño digital</option>
                            </select>
                        </div>
                        <div class="row mb-3" id="todos_insumos">
                            <label class="col-sm-2 col-form-label"><i class="bi bi-droplet me-2"></i>Insumos</label>
                            <div class="col-sm-12">
                                <select class="form-select" multiple aria-label="multiple select example"
                                    name="insumos[]" id="insumos">
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($insumos as $insumo)
                                        <option value="{{ $insumo->id_insumo }}">Código:{{ $insumo->id_insumo }} //
                                            {{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
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


    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title1">Herramientas</h5>
                    <div class="d-flex justify-content-between">
                        <h2 class="text-right"><i class="bi bi-wrench me-1"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{ route('herramientas.index') }}">Ver más...</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title1">Maquinaria</h5>
                    <div class="d-flex justify-content-between">
                        <h2 class="text-right"><i class="bi bi-gear"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{ route('maquinaria.index') }}">Ver más...</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title1">Insumos</h5>
                    <div class="d-flex justify-content-between">
                        <h2 class="text-right"><i class="bi bi-droplet"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{ route('insumos.index') }}">Ver más...</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("success-alert");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
            });
        </script>
    @endif



    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("success-danger");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
            });
        </script>
    @endif


    @if(session('seccion_vacia'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="seccion_vacia-alert">
            {{ session('seccion_vacia') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("seccion_vacia-alert");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
            });
        </script>
    @endif
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle"
                    style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Sección</th>
                            <th>Estatus</th>
                            <th>Insumos</th>
                            <th>Acciones</th>
                            <th>Asignar insumos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        @foreach ($maquinaria as $maquina)
                            <tr>
                                <th>{{ $maquina->id_maquinaria }}</th>
                                <td>{{ $maquina->Articulo_inventariados->Catalogo_articulos->nombre }}</td>
                                <td>{{ $maquina->Articulo_inventariados->Catalogo_articulos->seccion }}</td>
                                <td>{{ $maquina->Articulo_inventariados->estatus }}</td>
                                <td>
                                    @foreach ($maquina->insumos as $insumo)
                                        {{ $insumo->id_insumo }}
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm  " data-bs-toggle="modal"
                                        data-bs-target="#modal-update-{{ $maquina->id_maquinaria }}"><i
                                            class="fas fa-edit bt"></i></button>
                                    <button type="button" class="btn btn-outline-danger btn-sm  " data-bs-toggle="modal"
                                        data-bs-target="#modal-{{ $maquina->id_maquinaria }}"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-outline-primary  d-flex align-items-center mx-auto "
                                        data-bs-toggle="modal" data-bs-target="#modal-insumos{{ $maquina->id_maquinaria }}">
                                        <i class="fas fa-boxes me-2"></i>
                                    </button>

                                    </th>
                            </tr>

                            <!-- Modal Update -->
                            <div class="modal fade" id="modal-update-{{ $maquina->id_maquinaria }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar
                                                maquinaria
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3"
                                                action="{{ route('maquinaria.update', $maquina->id_maquinaria) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-6 mb-3">
                                                    <label for="id_maquinaria" class="form-label"><i
                                                            class="bi bi-gear me-2"></i>Código de maquinaria</label>
                                                    <input type="text" class="form-control" id="id_maquinaria"
                                                        name="id_maquinaria" value="{{ $maquina->id_maquinaria }}" disabled>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seccion" class="form-label"><i
                                                                class="bi bi-diagram-3 me-2"></i>Sección de la
                                                            maquinaria</label>
                                                        <input type="text" class="form-control" id="seccion" name="seccion"
                                                            value="{{ $maquina->Articulo_inventariados->Catalogo_articulos->seccion }}"
                                                            disabled>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="nombre" class="form-label"><i
                                                                class="bi bi-box-seam me-2"></i>Nombre de la
                                                            maquinaria</label>
                                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                                            value="{{ $maquina->Articulo_inventariados->Catalogo_articulos->nombre }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="estatus" class="form-label"><i
                                                            class="bi bi-check-circle me-2"></i>Estatus</label>
                                                    <input type="text" class="form-control" id="estatus" name="estatus"
                                                        value="{{ $maquina->Articulo_inventariados->estatus }}">
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="insumos" class="form-label"><i
                                                            class="bi bi-tools me-2"></i>Insumos</label>
                                                    @foreach ($maquina->insumos as $insumo)
                                                        <div class="row align-items-center mb-2">
                                                            <div class="col-md-3">
                                                                <span>{{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="capacidad-{{ $insumo->id_insumo }}"
                                                                    class="form-label">Capacidad</label>
                                                                <div class="input-group">
                                                                    <input type="text" id="capacidad-{{ $insumo->id_insumo }}"
                                                                        name="insumos[{{ $insumo->id_insumo }}]"
                                                                        class="form-control"
                                                                        value="{{ $insumo->pivot->capacidad }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Litros</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="cantidad-actual-{{ $insumo->id_insumo }}"
                                                                    class="form-label">Cantidad Actual</label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        id="cantidad-actual-{{ $insumo->id_insumo }}"
                                                                        name="insumos-cantidad-actual[{{ $insumo->id_insumo }}]"
                                                                        class="form-control"
                                                                        value="{{ $insumo->pivot->cantidad_actual }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Litros</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="cantidad-minima-{{ $insumo->id_insumo }}"
                                                                    class="form-label">Cantidad Mínima</label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        id="cantidad-minima-{{ $insumo->id_insumo }}"
                                                                        name="insumos-cantidad-minima[{{ $insumo->id_insumo }}]"
                                                                        class="form-control"
                                                                        value="{{ $insumo->pivot->cantidad_minima }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Litros</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
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
                            <!-- End Modal Update -->


                            <!-- Modal Delete -->
                            <div class="modal fade" id="modal-{{ $maquina->id_maquinaria }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de querer eliminar :
                                            {{ $maquina->Articulo_inventariados->Catalogo_articulos->nombre }}?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('maquinaria.destroy', $maquina->id_maquinaria) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Asignar Insumos -->
                            <div class="modal fade" id="modal-insumos{{ $maquina->id_maquinaria }}" tabindex="-1"
                                aria-labelledby="modalLabel{{ $maquina->id_maquinaria }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                            <h5 class="modal-title" id="modalLabel{{ $maquina->id_maquinaria }}"><i
                                                    class="bi bi-check-circle me-2"></i>Confirmación</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="{{ route('maquinaria.insumos_asignar', $maquina->id_maquinaria) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="insumos" class="form-label"><i
                                                            class="bi bi-box-seam me-2"></i>Insumos</label>
                                                    <select class="form-select" multiple
                                                        aria-label="multiple select example" id="insumos" name="insumos[]">
                                                        @foreach ($insumos as $insumo)
                                                            <option value="{{ $insumo->id_insumo }}">
                                                                {{$insumo->id_insumo}}//{{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-danger">Asignar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('machineForm').addEventListener('submit', function (event) {
            const insumos = document.getElementById('insumos');
            if (insumos.selectedOptions.length === 0) {
                alert('Seleccione al menos un insumo para la máquina.');
                event.preventDefault(); // Evita que el formulario se envíe
            }
        });
    </script>
    @endsection