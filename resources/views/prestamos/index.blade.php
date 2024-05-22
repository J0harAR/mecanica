
@extends('layouts.app')

@section('content')


<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal"><i class="ri-add-line"></i> Añadir</button>

@if (session('docente_no_encontrado')) 
          <div class="alert alert-danger" id="danger-alert">
            {{session('docente_no_encontrado')}}
        </div>
@endif
@if (session('herramienta_no_disponible'))
   
          <div class="alert alert-danger" id="danger-alert">
            {{session('herramienta_no_disponible')}}
        </div> 
@endif

@if (session('herramienta_no_encontrada'))
          <div class="alert alert-danger" id="danger-alert">
            {{session('herramienta_no_encontrada')}}
        </div>

@endif

@if (session('success'))
          <div class="alert alert-success" id="danger-alert">
            {{session('success')}}
        </div>
@endif






<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar prestamo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" action="{{ route('prestamos.store') }}" method="POST">
            @csrf
          
            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>RFC del docente</label>
              <input type="text" class="form-control" name="rfc" id="rfc">
            </div>


            <div class="col-md-6 mb-3">
              <label for="maquina" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione herramienta</label>
              <select class="form-select" aria-label="Seleccione una máquina" name="herramienta" id="herramienta">
                <option selected disabled>Seleccione la herramienta</option>
                @foreach ($herramientas as $herramienta)
                    <option value="{{ $herramienta->id_herramientas }}">
                    ID: {{ $herramienta->id_herramientas }} -
                    {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
                    </option>
                @endforeach
              </select>
            </div>


            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del prestamo</label>
              <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo">
            </div>

            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha de devolucion</label>
              <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion">
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




  <table class="table datatable">
        <thead>
            <tr>
                <th>RFC del Docente</th>
                <th>Nombre</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Herramienta</th>
                <th>Estatus</th>
                <th>Finalizar<th>
                
                

            </tr>
        </thead>
        <tbody>
            @foreach ($prestamos as $docente)
                @foreach ($docente->herramientas as $prestamo)
                    <tr>
                        <td>{{ $docente->rfc }}</td>
                        <td>{{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }} {{$docente->persona->apellido_m}}</td>              
                        <td>{{ $prestamo->pivot->fecha_prestamo }}</td>
                        <td>{{ $prestamo->pivot->fecha_devolucion }}</td>                      
                        <td>{{ $prestamo->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                        
                        <td> 
                          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                      data-bs-target="#modal-update-{{ $prestamo->pivot->id }}">
                                      <i class="fas fa-trash"></i>
                              </button>

                          

                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                              data-bs-target="#modal-delete{{ $prestamo->pivot->id}}"><i class="fas fa-trash"></i>
                            </button>
                        </td>

                        <td>{{ $prestamo->pivot->estatus }}</td>

                        <td><button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                              data-bs-target="#modal-finalizar{{ $prestamo->pivot->id}}"><i class="fas fa-trash"></i>
                          </button></td>
                        
                    </tr>



 <!-- Modal Finalizar-->
 <div class="modal fade" id="modal-finalizar{{ $prestamo->pivot->id}}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          ¿Estás seguro de finalizar el prestamo: {{ $prestamo->pivot->id}}?
          </div>
          <div class="modal-footer">
          <form action="{{ route('prestamos.finalizar',['id'=>$prestamo->pivot->id]) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Confirmar</button>
          </form>
          </div>
        </div>
        </div>
      </div><!-- End  Modal Finalizar -->




<!-- Vertically centered Modal -->

<div class="modal fade " id="modal-update-{{$prestamo->pivot->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar prestamo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" action="{{ route('prestamos.update',['id'=>$prestamo->pivot->id]) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>ID del prestamo</label>
              <input type="text" class="form-control" name="id_prestamo" id="id_prestamo" value="{{$prestamo->pivot->id}}" disabled>
            </div>
          
            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>RFC del docente</label>
              <input type="text" class="form-control" name="rfc" id="rfc" value="{{ $docente->rfc }}" readonly>
            </div>


            <div class="col-md-6 mb-3">
              <label for="maquina" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione herramienta</label>
              <select class="form-select" aria-label="Seleccione una máquina" name="herramienta" id="herramienta" disabled>
                <option selected >Seleccione la herramienta</option>
                        @foreach ($herramientas as $herramienta)
                                <option value="{{ $herramienta->id_herramientas }}"
                                    @if ($prestamo->id_herramientas == $herramienta->id_herramientas) selected @endif>
                                    {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
                                </option>
                          @endforeach
              </select>
            </div>
         
           
            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del prestamo</label>
              <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo" value="{{ $prestamo->pivot->fecha_prestamo }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha de devolucion</label>
              <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion" value="{{ $prestamo->pivot->fecha_devolucion }}">
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

 <!-- Modal -->
 <div class="modal fade" id="modal-delete{{ $prestamo->pivot->id}}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          ¿Estás seguro de querer eliminar el registro : {{ $prestamo->pivot->id}}?
          </div>
          <div class="modal-footer">
          <form action="{{ route('prestamos.destroy',['id'=>$prestamo->pivot->id]) }}" method="POST">
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
            @endforeach
        </tbody>
    </table>




@endsection
