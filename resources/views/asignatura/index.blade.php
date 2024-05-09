
@extends('layouts.app')

@section('content')

<p>Listar materias</p>


<form id="filterForm">
    <label for="nombre">Nombre completo</label>
    <input type="text" id="nombre" name="nombre">
    <label for="clave">Clave de materia</label>
    <input type="text" id="clave" name="clave">

    <button type="submit">Filtrar</button>
</form>




<table class="table table-sm">
  <thead>
    <tr>
      <th >Clave</th>
      <th >Nombre completo</th>
      <th>Editar</th>
      <th >Eliminar</th>

    </tr>
  </thead>
  <tbody>
    @foreach ($asignaturas as $asignatura)
    <tr>
        <td>{{$asignatura->clave}}</td>
        <td>{{$asignatura->nombre}}</td>
        <td>

               <a href="{{route('asignatura.edit', ['id'=>$asignatura->clave])}}">Editar</a>
        </td>
        <td>
            <form action="{{route('asignatura.destroy', ['id'=>$asignatura->clave])}}" method="POST">
                @csrf
                @method('DELETE')
                    <button>Eliminar</button>
            </form>
           
        </td>
    </tr>
    @endforeach
    
  </tbody>
</table>







@endsection