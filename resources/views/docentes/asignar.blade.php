@extends('layouts.app')

@section('content')

<form action="{{route('docentes.filtrar_asignaturas')}}" method="POST">
    @csrf
    <label for="">Periodo</label>

     <select  name="periodo">   
        @foreach ($periodos as $periodo)
            <option value="{{$periodo->clave}}">{{$periodo->clave}}</option>
                
        @endforeach  
        </select>

        <label for="">Docente</label>
        <select name="docente">   
        @foreach ($docentes as $docente)
            <option value="{{$docente->rfc}}">{{$docente->rfc}} / {{$docente->persona->apellido_p}} {{$docente->persona->apellido_m}} {{$docente->persona->nombre}}</option>
                
        @endforeach  
        </select>

        <label for="">Asignaturas</label>
        <select name="asignatura">   
        @foreach ($asignaturas as $asignatura)
            <option value="{{$asignatura->clave}}">{{$asignatura->clave}} / {{$asignatura->nombre}}</option>
                
        @endforeach  
        </select>

    <button>Siguiente</button>
        
  

</form>

@if(session('grupos'))

    @foreach ( session('grupos') as $grupo)
        {{$grupo}}
    @endforeach
<p></p>
@endif





@endsection