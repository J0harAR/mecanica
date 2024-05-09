@extends('layouts.app')

@section('content')


<form action="{{route('asignatura.update',['id'=>$asignatura->clave])}}" method="POST">
    @csrf
    @method('PATCH')
    <label for="">Nombre completo</label>
    <input type="text" name="nombre" id="nombre" value="{{$asignatura->nombre}}">



    <label for="">Clave</label>
    <input type="text" name="clave" id="clave" value="{{$asignatura->clave}}">

    <button>Guardar</button>

 
</form>










@endsection