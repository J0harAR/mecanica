
@extends('layouts.app')

@section('content')

<form action="" method="POST"> 
@csrf
        <label for="dimension_herramienta" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre">  
        
        <label for="condicion_herramienta" class="form-label">Apellido Parterno</label>
        <input type="text" class="form-control" id="apellido_p" name="apellido_p">

        <label for="tipo_insumo" class="form-label">Apellido Materno</label>
        <input type="text" class="form-control" id="apellido_m"  name="apellido_m">

        <label for="capacidad_insumo" class="form-label">Curp</label>
        <input type="text" class="form-control" id="curp" name="curp">

        <label for="">RFC</label>
        <input type="text">


        <label for="">Area</label>
        <input type="text">

        <label for="">Foto</label>
        <input type="file">

        <label for="">Telefono</label>
        <input type="text">
  
</form>
    




@endsection