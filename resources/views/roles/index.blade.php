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
                            <a href="{{ route('roles.edit',$role->id) }}" class="btn btn-warning">Editar</a>
                        @endcan

                        @can('borrar-rol')
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        @endcan
                       
                    </td>

                  </tr>
                    @endforeach
                 

                </tbody>
              </table>
       
      
            </div>
    </div>
  </div>
</div>
@endcan
@endsection