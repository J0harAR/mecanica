<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Articulo_inventariado;
class MaquinariaController extends Controller
{
  public function index()
  {
    

      $maquinaria = Maquinaria::with('Articulo_inventariados.Catalogo_articulos')->get();

      //foreach ($herramientas as $herramienta) {
        //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
    //  }
      
      return view('maquinaria.index',compact('maquinaria'));
  }
}
