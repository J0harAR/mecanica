@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Inventario</h1>
      
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal">Agregar articulo</button>

    </div>
            
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
                            <th>Codigo</th>
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


@endsection