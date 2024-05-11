
@extends('layouts.app')

@section('content')

<table class="table table-sm table-bordered ">
  <thead>
    <tr class="table-light  text-center">
      <th scope="col">RFC</th>
      <th scope="col">Nombre</th>
      <th scope="col">Area</th>
      <th scope="col">Datos generales</th>   
    </tr>
  </thead>
  <tbody>
    @foreach ($docentes as $docente)
    <tr class="text-center">
        <td scope="row">{{$docente->rfc}}</td>
        <td>{{$docente->persona->nombre}} {{$docente->persona->apellido_p}} {{$docente->persona->apellido_m}}</td>
        <td>{{$docente->area}}</td>
        <td>
            <a href="{{route('docentes.show',$docente->rfc)}}" class="btn btn-primary btn-sm ">
            <i class="bi bi-person-vcard"></i>
                                        Datos generales
            </a>
        </td>
    </tr>
    @endforeach
    
  </tbody>
</table>




@endsection