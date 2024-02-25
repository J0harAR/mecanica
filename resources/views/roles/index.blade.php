@extends('layouts.app')

@section('content')
@can('ver-rol')
<div class="pagetitle">
      <h1>Roles</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Roles</li>

        </ol>
      </nav>
</div>


@can('crear-rol')
    <a href="{{ route('roles.create') }}" class="btn btn-primary">Nuevo</a>

@endcan


<div class="row">
<div class="col-lg-4">
  <div class="card mt-4">
            <div class="card-body">
            
              <!-- Table with hoverable rows -->
              <table class="table table-hover mt-2">
                <thead>                  
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th> 
                </thead>
                <tbody>
                   @foreach($roles as $role)
                   <tr>
                    <td>{{$role->name}}</td>
                    <td>
                        @can('editar-rol')
                            <a href="{{ route('roles.edit',$role->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        @endcan

                        @can('borrar-rol')           
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{ $role->id}}">Eliminar</button>              
                        @endcan
                       
                    </td>

                  </tr>



                  <!-- MODAL -->

                  <div class="modal fade" id="modal-{{ $role->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       Estas seguro de eliminar el role: {{$role->name}}?
      </div>
      <div class="modal-footer">
      <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
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
       
      
            </div>
    </div>
  </div>
</div>
@endcan
@endsection