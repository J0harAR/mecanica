@extends('layouts.app')

@section('content')

<form action="{{route('docentes.filtrar_grupos')}}" method="POST">
    @csrf
    <label for="">Docente</label>
    <select name="rfc" id="rfc">
        @foreach ($docentes as $docente)
            <option value="{{$docente->rfc}}">{{$docente->persona->nombre}} {{$docente->persona->apellido_m}} {{$docente->persona->apellido_m}}</option>
        @endforeach

    </select>
   


    <label for="">Asignatura</label>

    <select name="id_asignatura" id="id_asignatura">
        @foreach ($asignaturas as $asignatura)
        <option value="{{$asignatura->clave}}">{{$asignatura->clave}} // {{$asignatura->nombre}}</option>
            
        @endforeach
    </select>


    <label for="">Periodo</label>

        <select  name="periodo">   
        <option disabled selected>Selecciona...</option>
        @foreach ($periodos as $periodo)
            <option value="{{$periodo->clave}}">{{$periodo->clave}}</option>
                
        @endforeach  
   </select>



    <button>Filtrar</button>
</form>


@if (session('grupos'))
<form action="{{route('docentes.eliminar_asignacion')}}" method="POST">
    @csrf

    <input type="text" value="{{session('docente')->rfc}}" readonly name="rfc">
    <input type="text" value="{{session('periodo')->clave}}" readonly name="periodo">

<table class="table table-sm table-bordered">
        <thead>
            <tr class="table-light text-center">
            <th scope="col">Materia</th>
            <th scope="col">Grupo</th>
            <th scope="col"> <button type="submit">Eliminar</button></th>
                  
            </tr>
        </thead>
        <tbody>
            @foreach (session('grupos') as $grupo)                      
            <tr class="text-center">
              
                <td>{{ session('asignatura')->clave}} / {{session('asignatura')->nombre}}</td>'
                <td>{{$grupo->clave}}</td>
                <td>
                    <input type="checkbox" name="grupos[{{ $grupo->clave }}][asignatura]" value="{{ session('asignatura')->clave }}">
                </td>
                                   
            </tr>
            @endforeach
           
        </tbody>
    </table>
    </form>
@endif








@endsection



