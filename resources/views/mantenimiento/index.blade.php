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

            <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Select</label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example" name="maquina" id="maquina">
                      <option selected>Open this select menu</option>
                      @foreach ($maquinarias as $maquinaria )
                        <option value="{{ $maquinaria->id_maquinaria }}">ID:{{$maquinaria->id_maquinaria}}{{$maquinaria->Articulo_inventariados->Catalogo_articulos->nombre}}</option>
                    @endforeach
                    </select>
                  </div>
            </div>


            <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Insumos</label>
                  <div class="col-sm-10">
                    <select class="form-select" multiple aria-label="multiple select example" name="insumos[]",id="insumos">
                    <option selected>Open this select menu</option>
                    @foreach ($insumos as $insumo)
                      <option value="{{$insumo->id_insumo}}">{{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}</option>
                    @endforeach 
                    
                    </select>
                  </div>
            </div>



            <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">fecha</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="fecha" id="fecha">
                  </div>
            </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>

                </div>
              </form><!-- End Multi Columns Form -->
        </div>
</div><!-- End card custom -->




</div>

@endsection