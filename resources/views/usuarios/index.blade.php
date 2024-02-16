@extends('layouts.app')

@section('content')
<div class="pagetitle">
      <h1>Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Usuarios</li>
       
        </ol>
      </nav>
</div>

<a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo</a>

<div class="card">
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
                                <h5><span>{{$rolName}}</span></h5>
                        @endforeach
                    @endif
                    </td>
                    <td>
                        <a href="{{ route('usuarios.edit',$usuario->id) }}" class="btn btn-info">Editar</a>
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>

                  </tr>
                    @endforeach
                 

                </tbody>
              </table>
            
              <div class="pagination justify-content-end">
                    {!! $usuarios->links() !!}
              </div>
              <!-- End Table with hoverable rows -->

            </div>
          </div>



@endsection