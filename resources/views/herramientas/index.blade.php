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
                        <i class="fas fa-wrench me-1"></i>Herramientas
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fas fa-plus-circle me-1"></i>Agregar artículo
            </button>

        </div>
    </div>

    <!-- Vertically centered Modal -->
    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Agregar artículo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('inventario.store') }}" method="POST">
                        @csrf
                        <div class="col-md-12 mb-3">
                            <label for="tipo" class="form-label"><i class="bi bi-gear me-2"></i>Tipo</label>
                            <select id="tipo" class="form-select" required name="tipo">
                                <option selected disabled>Selecciona un tipo</option>

                                <option value="Herramientas">Herramientas</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Nombre del
                                artículo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="cantidad" class="form-label"><i class="bi bi-stack me-2"></i>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad"required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="estatus" class="form-label"><i
                                    class="bi bi-check-circle me-2"></i>Estatus</label>
                            <select id="estatus" class="form-select" name="estatus">
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3" id="seccion" style="display: none;">
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
                            <div class="invalid-feedback">Campo obligatorio</div>
                        </div>
                        <div id="tipo_herramienta" style="display: none;" class="col-md-6 mb-3">
                            <label for="tipo_herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Tipo de
                                herramientas</label>
                            <select id="tipo_herramienta" class="form-select" name="tipo_herramienta"required>
                                <option selected disabled>Selecciona un tipo</option>
                                <option value="Herramienta de corte">Herramienta de corte</option>
                                <option value="Herramienta de golpe">Herramienta de golpe</option>
                                <option value="Herramienta de mantenimiento">Herramienta de mantenimiento</option>
                                <option value="Herramienta de maquinado">Herramienta de maquinado</option>
                                <option value="Herramienta de medición">Herramienta de medición</option>
                                <option value="Herramienta de montaje">Herramienta de montaje</option>
                                <option value="Herramienta de neumáticos">Herramienta de neumáticos</option>
                                <option value="Herramienta de seguridad">Herramienta de seguridad</option>
                                <option value="Herramienta de sujeción">Herramienta de sujeción</option>
                                <option value="Herramienta de torno">Herramienta de torno</option>
                                <option value="Herramienta eléctrica">Herramienta eléctrica</option>
                                <option value="Herramienta manual">Herramienta manual</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="dimensionHerramienta" style="display:none;">
                            <label for="dimension_herramienta" class="form-label"><i
                                    class="bi bi-rulers me-2"></i>Dimensión</label>
                            <input type="number" class="form-control" id="dimension_herramienta"
                                name="dimension_herramienta"required>
                        </div>
                        <div class="col-md-12 mb-3" id="condicionHerramienta" style="display:none;">
                            <label for="condicion_herramienta" class="form-label"><i
                                    class="bi bi-activity me-2"></i>Condición</label>
                            <input type="text" class="form-control" id="condicion_herramienta_input"
                                name="condicion_herramienta">
                        </div>
                        <div class="col-md-12 mb-3" id="tipoMaquina" style="display:none;">
                            <label for="tipo_maquina" class="form-label"><i class="bi bi-robot me-2"></i>Tipo de
                                máquina</label>
                            <input type="text" class="form-control" id="tipo_maquina" name="tipo_maquina">
                        </div>
                        <div class="row mb-3" id="todos_insumos" style="display:none;">
                            <label class="col-sm-2 col-form-label"><i class="bi bi-droplet me-2"></i>Insumos</label>
                            <div class="col-sm-12">
                                <select class="form-select" multiple aria-label="multiple select example"
                                    name="insumos[]" id="insumos" required>
                                    <option selected disabled>Open this select menu</option>
                                    @foreach ($insumos as $insumo)
                                        <option value="{{ $insumo->id_insumo }}">Código:{{ $insumo->id_insumo }} //
                                            {{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3" id="tipoInsumo" style="display:none;">
                            <label for="tipo_insumo" class="form-label"><i class="bi bi-fuel-pump me-2"></i>Tipo de
                                insumo</label>
                            <input type="text" class="form-control" id="tipo_insumo" name="tipo_insumo">
                        </div>
                        <div class="col-md-4 mb-3" id="capacidadInsumo" style="display:none;">
                            <label for="capacidad_insumo" class="form-label"><i
                                    class="bi bi-speedometer2 me-2"></i>Capacidad</label>
                            <input type="number" class="form-control" id="capacidad_insumo" name="capacidad_insumo">
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #002855; border-color: #002855;">Guardar
                            </button>
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
                        <h2 class="text-right"><i class="bi bi-hammer"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{route('herramientas.index')}}">Ver mas...</a></p>
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
                            <p class="m-b-o text-right"><a href="{{route('maquinaria.index')}}">Ver mas...</a></p>
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
                            <p class="m-b-o text-right"><a href="{{route('insumos.index')}}">Ver mas...</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('tipo_vacia'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('tipo_vacia') }}
        </div>
    @endif 
    
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                        <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dimensión</th>
                        <th>Condición</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody  class="bg-light">
                    @foreach ($herramientas as $herramienta)
                        <tr>
                            <th>{{$herramienta->id_herramientas}}</th>
                            <td>{{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                            <td>{{$herramienta->dimension}}</td>
                            <td>{{$herramienta->condicion}}</td>
                            <td>{{$herramienta->Articulo_inventariados->estatus}}</td>
                            <td class="text-center">

                                <button type="button" class="btn btn-outline-primary btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#modal-update-{{ $herramienta->id_herramientas}}"><i
                                        class="fas fa-edit bt"></i></button>


                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $herramienta->id_herramientas}}"><i
                                        class="fas fa-trash"></i></button>



                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modal-{{ $herramienta->id_herramientas}}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de querer eliminar la herramienta:
                                        {{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('herramientas.destroy', $herramienta->id_herramientas) }}"
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
                        </div><!-- End Modal -->


                        <!-- Vertically centered Modal -->
                        <div class="modal fade" id="modal-update-{{ $herramienta->id_herramientas}}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar herramienta
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3"
                                            action="{{ route('herramientas.update', $herramienta->id_herramientas) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-6 mb-3">
                                                <label for="codigo_herramienta" class="form-label"><i
                                                        class="bi bi-tag me-2"></i>Código de herramienta</label>
                                                <input type="text" class="form-control" id="codigo_herramienta"
                                                    name="codigo_herramienta" value="{{ $herramienta->id_herramientas }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="nombre_herramienta" class="form-label"><i
                                                        class="bi bi-box me-2"></i>Nombre de la herramienta</label>
                                                <input type="text" class="form-control" id="nombre_herramienta"
                                                    name="nombre_herramienta"
                                                    value="{{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="condicion_herramienta" class="form-label"><i
                                                        class="bi bi-activity me-2"></i>Condición</label>
                                                <input type="text" class="form-control" id="condicion_herramienta"
                                                    name="condicion_herramienta" value="{{ $herramienta->condicion }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="estatus" class="form-label"><i
                                                        class="bi bi-check-circle me-2"></i>Estatus</label>
                                                <select id="estatus" class="form-select" name="estatus">
                                                    <option value="Disponible" {{ $herramienta->Articulo_inventariados->estatus == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                                    <option value="No disponible" {{ $herramienta->Articulo_inventariados->estatus == 'No disponible' ? 'selected' : '' }}>No disponible</option>
                                                </select>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary"
                                                    style="background-color: #002855; border-color: #002855;">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Vertically centered Modal -->




                    @endforeach


                </tbody>
            </table>
        </div>
    </div>



</div>

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.setTimeout(function () {
                const successAlert = document.getElementById("success-alert");
                if (successAlert) successAlert.style.display = 'none';
            }, 3000);
        });
    </script>
@endif




<script>
    document.addEventListener('DOMContentLoaded', function () {

        var tipoSelect = document.getElementById('tipo');

        var tipo_herramienta = document.getElementById('tipo_herramienta');


        var tipo_maquina = document.getElementById('tipoMaquina');

        var tipo_insumo = document.getElementById('tipoInsumo');

        var capacidad_insumo = document.getElementById('capacidadInsumo');

        var dimension_herramienta = document.getElementById('dimensionHerramienta');

        var condicion_herramienta = document.getElementById('condicionHerramienta');

        var seccion = document.getElementById('seccion');

        var todos_insumos = document.getElementById('todos_insumos');

        var condicion_herramienta_input=document.getElementById('condicion_herramienta_input')

        tipoSelect.addEventListener('change', function () {

            switch (this.value) {
                case 'Herramientas':
                    showElement(tipo_herramienta);
                    showElement(dimension_herramienta);
                    showElement(condicion_herramienta);
                    condicion_herramienta_input.required =true;
                    hideElements([tipo_maquina, tipo_insumo, capacidad_insumo, seccion, todos_insumos]);
                    break;
                case 'Maquinaria':
                    showElement(tipo_maquina);
                    showElement(seccion);
                    showElement(todos_insumos);
                    hideElements([tipo_herramienta, tipo_insumo, capacidad_insumo, dimension_herramienta, condicion_herramienta]);

                    break;
                case 'Insumos':
                    showElement(tipo_insumo);
                    showElement(capacidad_insumo);
                    hideElements([tipo_herramienta, tipo_maquina, dimension_herramienta, condicion_herramienta, seccion, todos_insumos]);
                    break;
                default:

                    break;
            }

            function showElement(element) {
                element.style.display = 'block';
            }

            function hideElements(elements) {
                elements.forEach(element => {
                    element.style.display = 'none';
                });
            }


        });
    });
</script>
@endsection