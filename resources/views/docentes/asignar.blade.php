@extends('layouts.app')

@section('content')

<form action="{{route('docentes.filtrar_asignaturas')}}" method="POST">
    @csrf
    <label for="">Periodo</label>

     <select  name="periodo">   
     <option disabled selected>Selecciona...</option>
        @foreach ($periodos as $periodo)
            <option value="{{$periodo->clave}}">{{$periodo->clave}}</option>
                
        @endforeach  
        </select>

        <label for="">Docente</label>
        <select name="docente">   
        <option disabled selected>Selecciona...</option>
        @foreach ($docentes as $docente)
            <option value="{{$docente->rfc}}">{{$docente->rfc}} / {{$docente->persona->apellido_p}} {{$docente->persona->apellido_m}} {{$docente->persona->nombre}}</option>
                
        @endforeach  
        </select>

        <label for="">Asignaturas</label>
        <select name="asignatura">   
        <option disabled selected>Selecciona...</option>
        @foreach ($asignaturas as $asignatura)
            <option value="{{$asignatura->clave}}">{{$asignatura->clave}} / {{$asignatura->nombre}}</option>
                
        @endforeach  
        </select>

    <button>Siguiente</button>        
</form>



@if(session('grupos') and (session('docente')))

    <form action="{{ route('docentes.asignar') }}" method="POST">
        
        @csrf
    
        <input type="hidden" name="rfc_docente" value="{{ session('docente')->rfc }}">
        <input type="hidden" name="clave_periodo" value="{{ session('periodo')->clave }}">

        <table class="table table-sm table-bordered">
        <thead>
            <tr class="table-light text-center">
                <th scope="col">RFC</th>
                <th scope="col">Nombre completo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td>{{ session('docente')->rfc }}</td>
                <td>{{ session('docente')->persona->nombre }}</td>
            </tr>
        </tbody>
    </table>


        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-light text-center">
                    <th scope="col">Clave</th>
                    <th scope="col">Nombre completo</th>
                    <th scope="col">Grupo</th>
                    <th scope="col"> <button type="submit">Asignar Seleccionados</button></th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('grupos') as $grupo)
                    <tr class="text-center">
                        <td scope="row">{{ $grupo->asignaturas[0]->clave}}</td>
                        <td>{{$grupo->asignaturas[0]->nombre}}</td>
                        <td>{{$grupo->clave}}</td>
                       
                        <td>
                            <input type="checkbox" name="asignaturas[]" value="{{ $grupo->asignaturas[0]->clave}}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
@endif

@endsection