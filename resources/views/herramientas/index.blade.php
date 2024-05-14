@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Inventario</h1>
      
        <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modal"><i class="ri-add-line"></i>Agregar articulo</button>

    </div>

<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Agregar artículo</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                        <form class="row g-3" action="{{route('inventario.store')}}" method="POST">
                        @csrf
                        <div class="col-md-12">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select id="tipo" class="form-select" required name="tipo">
                                    <option selected disabled>Selecciona un tipo</option>
                                    <option value="Insumos">Insumos</option>
                                    <option value="Maquinaria">Maquinaria</option>
                                     <option value="Herramientas">Herramientas</option>
                                </select>

                            </div>
                        
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

                           



                            <div class="col-md-12" id="seccion" style="display: none;">
                                <label for="seccion" class="form-label">Seccion</label>
                                    <select id="seccion" class="form-select" name="seccion">
                                    <option selected disabled>Selecciona una sección </option>
                                        <option value="03">03 Metrología II </option>
                                        <option value="04">04 Mecánica de materiales</option>
                                        <option value="05">05 Mantenimiento</option>
                                        <option value="06">06 Robots industriales</option>
                                        <option value="07">07 Mecánica de materiales</option>
                                        <option value="08">08 Manufactura sustractiva</option>
                                        <option value="09">09 Manufactura adidtiva</option>
                                        <option value="12">12 Mecánica de fluidos y termodinámica</option>
                                        <option value="13">13 Neumática</option>
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
                                <option value="Herramienta de neumaticos">Herramienta de neumáticos</option>
                                <option value="Herramienta de seguridad">Herramienta de seguridad</option>
                                <option value="Herramienta de sujecion">Herramienta de sujeción</option>
                                <option value="Herramienta de torno">Herramienta de torno</option>
                                <option value="Herramienta Electrica">Herramienta Eléctrica</option>
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
                                <label for="tipo_maquina" class="form-label">Tipo de máquina</label>
                                <input type="text" class="form-control" id="tipo_maquina"  name="tipo_maquina">
                            </div>

                            <div class="row mb-3" id="todos_insumos" style="display:none;">
                                <label class="col-sm-2 col-form-label">Insumos</label>
                                <div class="col-sm-12">
                                    <select class="form-select" multiple aria-label="multiple select example" name="insumos[]",id="insumos">
                                    <option selected>Open this select menu</option>
                                    @foreach ($insumos as $insumo)
                                    <option value="{{$insumo->id_insumo}}">{{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}</option>
                                    @endforeach 
                                    
                                    </select>
                                </div>
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
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
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
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
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
                            <th>Dimensión</th>                        
                            <th>Condición</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($herramientas  as $herramienta )
                        <tr>
                            <th>{{$herramienta->id_herramientas}}</th>
                            <td>{{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                            <td>{{$herramienta->dimension}}</td>
                            <td>{{$herramienta->condicion}}</td>
                            <td>{{$herramienta->Articulo_inventariados->estatus}}</td>
                            <td> 
                              
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-update-{{ $herramienta->id_herramientas}}"><i class="fas fa-edit bt"></i></button>


                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-{{ $herramienta->id_herramientas}}"><i class="fas fa-trash"></i></button>


                               
                            </td>
                        </tr>  

                          <!-- Modal -->
                        <div class="modal fade" id="modal-{{ $herramienta->id_herramientas}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    ¿Estás seguro de querer eliminar la herramienta: {{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}?
                                    </div>
                                    <div class="modal-footer">
                                    <form action="{{ route('herramientas.destroy', $herramienta->id_herramientas) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div><!-- End Modal -->


                        <!-- Vertically centered Modal -->
  <div class="modal fade" id="modal-update-{{ $herramienta->id_herramientas}}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Agregar artículo</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                        <form class="row g-3" action="{{route('herramientas.update',$herramienta->id_herramientas)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                                <label for="estatus" class="form-label">Código de herramienta</label>
                                <input type="text" class="form-control" id="estatus" name="estatus" value="{{$herramienta->id_herramientas}}" disabled>
                        </div>

                        <div class="col-md-12">
                                <label for="estatus" class="form-label">Nombre de la herramienta</label>
                                <input type="text" class="form-control" id="estatus" name="estatus" value="{{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}" disabled>
                          </div>
                          
                          <div class="col-md-12" id="condicionHerramienta">
                                <label for="condicion_herramienta" class="form-label">Condición</label>
                                <input type="text" class="form-control" id="condicion_herramienta" name="condicion_herramienta" value="{{$herramienta->condicion}}">
                            </div>

                            <div class="col-md-12">
                                <label for="estatus" class="form-label">Estatus</label>
                                <input type="text" class="form-control" id="estatus" name="estatus" value="{{$herramienta->Articulo_inventariados->estatus}}">
                            </div>

                            

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">Guardar</button>                               
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->



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
document.addEventListener('DOMContentLoaded', function() {
   
    var tipoSelect = document.getElementById('tipo');
   
    var tipo_herramienta = document.getElementById('tipo_herramienta');


    var tipo_maquina=document.getElementById('tipoMaquina');

    var tipo_insumo=document.getElementById('tipoInsumo');

    var capacidad_insumo=document.getElementById('capacidadInsumo');

    var dimension_herramienta=document.getElementById('dimensionHerramienta');

    var condicion_herramienta=document.getElementById('condicionHerramienta');

    var seccion=document.getElementById('seccion');

    var todos_insumos=document.getElementById('todos_insumos');


    tipoSelect.addEventListener('change', function() {
        
    switch (this.value) {
        case 'Herramientas':
            showElement(tipo_herramienta);
            showElement(dimension_herramienta);
            showElement(condicion_herramienta);
            
            hideElements([tipo_maquina, tipo_insumo,capacidad_insumo,seccion,todos_insumos]);
            break;
        case 'Maquinaria':
            showElement(tipo_maquina);
            showElement(seccion);
            showElement(todos_insumos);
            hideElements([tipo_herramienta, tipo_insumo,capacidad_insumo,dimension_herramienta,condicion_herramienta]);
           
            break;
        case 'Insumos':
            showElement(tipo_insumo);
            showElement(capacidad_insumo);
            hideElements([tipo_herramienta, tipo_maquina,dimension_herramienta,condicion_herramienta,seccion,todos_insumos]);
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