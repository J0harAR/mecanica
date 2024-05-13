@extends('layouts.app')
@section('content')

<style>
    .custom-card {
      max-width: 700px;
      margin: 0 auto;
      margin-top: 100px;
    }
</style>

<div class="container py-5">
    <h1 class="fw-bold mb-0" style="color: #343a40;">Mantenimiento</h1>


    <div class="card custom-card">
    <div class="card-body">
        <h5 class="card-title">Registrar mantenimiento</h5>

      
        <!-- Multi Columns Form -->
        <form class="row g-3" action="{{route('mantenimiento.store')}}" method="POST">
            @csrf

            <div class="col-md-6">
                  <label class="form-label">Select</label>
                    <select class="form-select" aria-label="Default select example" name="maquina" id="maquina">
                      <option selected>Open this select menu</option>
                      @foreach ($maquinarias as $maquinaria )
                        <option value="{{ $maquinaria->id_maquinaria }}">ID:{{$maquinaria->id_maquinaria}}{{$maquinaria->Articulo_inventariados->Catalogo_articulos->nombre}}</option>
                    @endforeach
                    </select>

            </div>

            <div class="col-md-6">
                  <label for="inputDate" class="form-label">fecha</label>               
                    <input type="date" class="form-control" name="fecha" id="fecha">
            </div>

            <div class="col-md-12">             
                <label for="insumos" class="form-label text-center">Insumos</label>
                @foreach ($insumos as $insumo)
                <div class="row mb-3">
                    <div class="col-md-5 d-flex align-items-center">
                        <input type="checkbox" name="{{$insumo->id_insumo}}" data-id="{{$insumo->id_insumo}}" class="insumo-enable me-2">
                        <span>{{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}</span>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" name="insumos[{{$insumo->id_insumo}}]" placeholder="Cantidad" data-id="{{$insumo->id_insumo}}" class="insumo-cantidad form-control" placeholder="cantidad" disabled>
                            <span class="input-group-text">Litros</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
           
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Guardar</button>

                </div>
              </form><!-- End Multi Columns Form -->
        </div>
</div><!-- End card custom -->




</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.insumo-enable').on('click', function(){
            let id = $(this).attr('data-id');
            let enable = $(this).is(":checked");
            $('.insumo-cantidad[data-id="' + id + '"]').attr('disabled', !enable);
            $('.insumo-cantidad[data-id="' + id + '"]').val(null);
        });
    });
</script>



@endsection