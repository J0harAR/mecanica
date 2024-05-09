@extends('layouts.app')

@section('content')


<form action="{{route('asignatura.store')}}" method="POST">
    @csrf

    <label for="">Nombre completo</label>
    <input type="text" name="nombre" id="nombre">



    <label for="">Clave</label>
    <input type="text" name="clave" id="clave">

    <button>Guardar</button>

 
</form>










@endsection