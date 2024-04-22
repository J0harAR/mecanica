@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0" style="color: #343a40;">Inventario</h1>
      
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal"><i class="ri-add-line"></i>Agregar articulo</button>

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
<div class="row">
    <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Herramientas</h5>
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
              <h5 class="card-title">Maquinaria</h5>
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
              <h5 class="card-title">Insumos</h5>
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

    <div class="card">
            <div class="card-body">
            
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>                       
                            <th>Tipo</th>                         
                            <th>Acciones</th>                       
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($catalogo_articulo  as $articulo )
                        <tr>
                            <td>{{$articulo->id_articulo}}</td>
                            <td>{{$articulo->nombre}}</td>
                            <td>{{$articulo->cantidad}}</td>
                            <td>{{$articulo->tipo}}</td>
                            <td>                          
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-{{ $articulo->id_articulo}}"><i class="fas fa-trash"></i></button>
                            </td>
                           
                        </tr>  

                         <!-- Modal -->
                         <div class="modal fade" id="modal-{{ $articulo->id_articulo}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    ¿Estás seguro de querer eliminar : {{$articulo->nombre}}?
                                    </div>
                                    <div class="modal-footer">
                                    <form action="{{ route('inventario.destroy', $articulo->id_articulo) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div><!-- End Modal -->












                        @endforeach
                       
                        </tbody>
                    </table>
             </div>
    </div>
    















<!-- Vertically centered Modal -->
              <div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Agregar artículo</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                        <form class="row g-3" action="{{route('inventario.store')}}" method="POST">
                        @csrf
                            <div class="col-md-12">
                                <label for="nombre" class="form-label">Nombre del articulo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"required autocomplete="nombre" autofocus>

                            </div>

                            <div class="col-md-12">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad">

                            </div>


                            <div class="col-md-12">
                                <label for="estatus" class="form-label">Estatus</label>
                                <input type="text" class="form-control" id="estatus" name="estatus">

                            </div>

                            <div class="col-md-12">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select id="tipo" class="form-select" required name="tipo">
                                    <option selected disabled>Selecciona un tipo</option>
                                    <option value="Insumos">Insumos</option>
                                    <option value="Maquinaria">Maquinaria</option>
                                     <option value="Herramientas">Herramientas</option>
                                </select>

                            </div>



                            <div class="col-md-12" id="seccion" style="display: none;">
                                <label for="seccion" class="form-label">Seccion</label>
                                    <select id="seccion" class="form-select" name="seccion">
                                    <option selected disabled>Selecciona una seccion </option>
                                        <option value="03">03 Metrologia II </option>
                                        <option value="04">04 Mecanica de materiales</option>
                                        <option value="05">05 Mantenimiento</option>
                                        <option value="06">06 Robots industriales</option>
                                        <option value="07">07 Mecanica de materiales</option>
                                        <option value="08">08 Manufactura sustractiva</option>
                                        <option value="09">09 Manufactura adidtiva</option>
                                        <option value="12">12 Mecanica de fluidos y termodinamica</option>
                                        <option value="13">13 Neumatica</option>
                                        <option value="20">20 Área de diseño digital</option>
                                    </select>
                                    <div class="invalid-feedback">Campo obligatorio</div>

                            </div>

                            

                            <div id="tipo_herramienta" style="display: none;" class="col-md-6">
                                <label for="tipo_herramienta" class="form-label">Tipo de herramientas</label>
                                <select id="tipo_herramienta" class="form-select" name="tipo_herramienta">
                                <option selected disabled>Selecciona un tipo</option>
                                <option value="Herramienta de corte">Herramienta de corte</option>
                                <option value="Herramienta de golpe">Herramienta de golpe</option>
                                <option value="Herramienta de mantenimimiento">Herramienta de mantenimimiento</option>
                                <option value="Herramienta de maquinado">Herramienta de maquinado</option>
                                <option value="Herramienta de medición">Herramienta de medición</option>
                                <option value="Herramienta de montaje">Herramienta de montaje</option>
                                <option value="Herramienta de neumaticos">Herramienta de neumaticos</option>
                                <option value="Herramienta de seguridad">Herramienta de seguridad</option>
                                <option value="Herramienta de sujecion">Herramienta de sujeción</option>
                                <option value="Herramienta de torno">Herramienta de torno</option>
                                <option value="Herramienta Electrica">Herramienta Electrica</option>
                                <option value="Herramienta manual">Herramienta manual</option>
                                </select>
                            </div>  

                            <div class="col-md-6" id="dimensionHerramienta" style="display:none;">
                                <label for="dimension_herramienta" class="form-label">Dimensión</label>
                                <input type="number" class="form-control" id="dimension_herramienta" name="dimension_herramienta">
                            </div>

                            <div class="col-md-12" id="condicionHerramienta" style="display:none;">
                                <label for="condicion_herramienta" class="form-label">Condición</label>
                                <input type="text" class="form-control" id="condicion_herramienta" name="condicion_herramienta">
                            </div>



                            <div class="col-md-12" id="tipoMaquina" style="display:none;">
                                <label for="tipo_maquina" class="form-label">Tipo de maquina</label>
                                <input type="text" class="form-control" id="tipo_maquina"  name="tipo_maquina">
                            </div>

                            <div class="col-md-8" id="tipoInsumo" style="display:none;">
                                <label for="tipo_insumo" class="form-label">Tipo de insumo</label>
                                <input type="text" class="form-control" id="tipo_insumo"  name="tipo_insumo">
                            </div>

                            <div class="col-md-4" id="capacidadInsumo" style="display:none;">
                                <label for="capacidad_insumo" class="form-label">Capacidad</label>
                                <input type="number" class="form-control" id="capacidad_insumo" name="capacidad_insumo">
                            </div>


                   
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Guardar</button>                               
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
</div>
<script>
document.getElementById("formHerramienta").addEventListener("submit", function(event) {
  var nombre = document.getElementById("nombreHerramienta").value;
  var cantidad = document.getElementById("cantidadHerramienta").value;
  
  if (!nombre || cantidad <= 0) {
    alert("Todos los campos son obligatorios y la cantidad debe ser mayor que cero.");
    event.preventDefault(); // Evita que el formulario se envíe
  }
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
   
    var tipoSelect = document.getElementById('tipo');
   
    var tipo_herramienta = document.getElementById('tipo_herramienta');


    var tipo_maquina=document.getElementById('tipoMaquina');

    var tipo_insumo=document.getElementById('tipoInsumo');

    var capacidad_insumo=document.getElementById('capacidadInsumo');

    var dimension_herramienta=document.getElementById('dimensionHerramienta');

    var condicion_herramienta=document.getElementById('condicionHerramienta');

    var seccion=document.getElementById('seccion');


    tipoSelect.addEventListener('change', function() {
        
    switch (this.value) {
        case 'Herramientas':
            showElement(tipo_herramienta);
            showElement(dimension_herramienta);
            showElement(condicion_herramienta);
            
            hideElements([tipo_maquina, tipo_insumo,capacidad_insumo,seccion]);
            break;
        case 'Maquinaria':
            showElement(tipo_maquina);
            showElement(seccion);
            hideElements([tipo_herramienta, tipo_insumo,capacidad_insumo,dimension_herramienta,condicion_herramienta]);
           
            break;
        case 'Insumos':
            showElement(tipo_insumo);
            showElement(capacidad_insumo);
            hideElements([tipo_herramienta, tipo_maquina,dimension_herramienta,condicion_herramienta,seccion]);
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





@endsection