@extends('layouts.app')

@section('content')

<form action="{{route('practicasAlumno.store')}}" method="post">
    @csrf
<label for="">Numero de control</label>
<input type="text" name="no_control" id="no_control">



<select name="practica">
    @foreach ($practicas as $practica)
            <option value="{{$practica->id_practica}}">{{$practica->id_practica}}//{{$practica->nombre}}</option>
    @endforeach

</select>
<label for="">Fecha</label>
<input type="date" name="fecha" id="fecha">

<label for="">Numero de equipo</label>
<input type="number" name="no_equipo" id="no_equipo">


<label for="">Hora de entrada</label>
<input type="time" name="hora_entrada" id="hora_entrada">

<label for="">Hora de salida</label>
<input type="time" name="hora_salida" id="hora_salida">





    <select class="form-select" multiple aria-label="multiple select example" name="articulos[]",id="articulos">
             <option selected>Open this select menu</option>
                    @foreach ($articulos_inventariados as $articulo)
                        <option value="{{ $articulo->id_inventario }}">Codigo:{{ $articulo->id_inventario }} //
                         {{ $articulo->Catalogo_articulos->nombre }}</option>
                    @endforeach
     </select>

     <button>Guardar</button>

</form>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('alumno_no_encontrado'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('alumno_no_encontrado') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



@endsection