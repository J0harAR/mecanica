@extends('layouts.app')

@section('content')

<form action="{{route('practicasAlumno.store')}}" method="post">
    @csrf

    <select name="practica">
    @foreach ($practicas as $practica)
            <option value="{{$practica->id_practica}}">{{$practica->id_practica}}//{{$practica->nombre}}</option>
    @endforeach

</select>


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
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@endsection