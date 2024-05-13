@extends('layouts.app')
@section('content')

<div class="container py-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0" style="color: #343a40;">Matenimientos</h1>
    <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modal"><i class="ri-add-line"></i></button>
    </div>


<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Agregar alumno</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                    <form class="row g-3" action="{{route('mantenimiento.store')}}" method="POST">
            @csrf

            <div class="col-md-6">
                  <label class="form-label">Select</label>
                    <select class="form-select" aria-label="Default select example" name="maquina" id="maquina">
                      <option selected>Open this select menu</option>
                      @foreach ($maquinarias as $maquinaria )
                        <option value="{{ $maquinaria->id_maquinaria }}">ID:{{$maquinaria->id_maquinaria}}{{$maquinaria->Articulo_inventariados->Catalogo_articulos->nombre}}</option>
                    @endforeach
                    </select>

            </div>

            <div class="col-md-6">
                  <label for="inputDate" class="form-label">fecha</label>               
                    <input type="date" class="form-control" name="fecha" id="fecha">
            </div>

            <div class="col-md-12">             
                <label for="insumos" class="form-label text-center">Insumos</label>
                @foreach ($insumos as $insumo)
                <div class="row mb-3">
                    <div class="col-md-5 d-flex align-items-center">
                        <input type="checkbox" name="{{$insumo->id_insumo}}" data-id="{{$insumo->id_insumo}}" class="insumo-enable me-2">
                        <span>{{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}</span>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" name="insumos[{{$insumo->id_insumo}}]" placeholder="Cantidad" data-id="{{$insumo->id_insumo}}" class="insumo-cantidad form-control" placeholder="cantidad" disabled>
                            <span class="input-group-text">Litros</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
           
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Guardar</button>

                </div>
              </form><!-- End Multi Columns Form -->
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->



<div class="card">
    <div class="card-body">

  <table class="table datatable">
          <thead>
              <tr>
                <th>N.Mantenimiento</th>
                <th>Maquina</th>
                <th>Insumos Utilizados</th>
                <th data-type="date" data-format="YYYY/DD/MM">Fecha</th>
                <th>Borrar</th>
              </tr>
          </thead>
        <tbody>
              @foreach ($mantenimientos as $mantenimiento)
              <tr>
                  <td>{{$mantenimiento->id}}</td>
                  <td>{{$mantenimiento->Maquinarias->id_maquinaria}}/{{$mantenimiento->Maquinarias->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                  <td><button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-{{ $mantenimiento->id}}"><i class="bi bi-droplet"></i></button>
                  </td>
                  <td>{{$mantenimiento->fecha}}</td> 
                  <td><button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-delete{{ $mantenimiento->id}}"><i class="fas fa-trash"></i></button></td>    
                  
                  
              </tr>

               <!-- Modal -->
               <div class="modal fade" id="modal-{{ $mantenimiento->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Insumos utilizados</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="col-md-12">

                                  @foreach ($mantenimiento->insumos as $insumo)
                                      <div class="row">
                                          <div class="col-md-6 mt-2"> 
                                              {{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}
                                          </div>
                                          <div class="col-md-4 mt-2">  
                                              <input type="text" name="insumos[{{$insumo->id_insumo}}]" class="form-control" value="{{$insumo->pivot->cantidad}}" name="cantidad" disabled>
                                              
                                          </div>
                                          <div class="col-md-2 mt-2">
                                            <p>Litros</p>
                                          </div>
                                      </div>
                                  @endforeach                                                     
                              </div>
                                    </div>
                                    <div class="modal-footer">
  
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                                </div>
                            </div><!-- End Modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="modal-delete{{ $mantenimiento->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    ¿Estás seguro de querer eliminar : {{$mantenimiento->id}}?
                                    </div>
                                    <div class="modal-footer">
                                    <form action="{{ route('mantenimiento.destroy', $mantenimiento->id) }}" method="POST">
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
</div>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.insumo-enable').on('click', function(){
            let id = $(this).attr('data-id');
            let enable = $(this).is(":checked");
            $('.insumo-cantidad[data-id="' + id + '"]').attr('disabled', !enable);
            $('.insumo-cantidad[data-id="' + id + '"]').val(null);
        });
    });
</script>


@endsection