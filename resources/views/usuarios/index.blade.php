@extends('layouts.app')

@section('content')
@can('ver-usuarios')
<div class="pagetitle">
      <h1>Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Usuarios</li>
       
        </ol>
      </nav>
</div>


@can('crear-usuarios')
<a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo</a>
@endcan
<div class="card mt-4">
            <div class="card-body ">
            
              <!-- Table with hoverable rows -->
              <table class="table table-hover">
                <thead>
                  <tr>
                   
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                   @foreach($usuarios as $usuario)
                   <tr>
                    <td>{{$usuario->name}}</td>
                    <td>{{$usuario->email}}</td>
                    <td>
                    @if(!empty($usuario->getRoleNames()))
                        @foreach($usuario->getRoleNames() as $rolName)
                                <h6><span>{{$rolName}}</span></h6>
                        @endforeach
                    @endif
                    </td>
                    <td>
                        @can('editar-usuarios')                                               
                        <a href="{{ route('usuarios.edit',$usuario->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        @endcan

                        @can('borrar-usuarios')                   
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{ $usuario->id}}">Eliminar</button>
                        @endcan
                    </td>

                  </tr>



                <!--MODAL-->

                <div class="modal fade" id="modal-{{ $usuario->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       Estas seguro de eliminar el usuario: {{$usuario->name}}?
      </div>
      <div class="modal-footer">
      <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
          @method('DELETE')
          @csrf
          
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          
          <button type="submit" class="btn btn-success">Aceptar</button>
        </form>
      </div>
    </div> 
  </div>
</div>











                    @endforeach
                 

                </tbody>
              </table>
            
              <div class="pagination justify-content-end">
                    {!! $usuarios->links() !!}
              </div>
              <!-- End Table with hoverable rows -->

            </div>
          </div>


          @endcan
@endsection