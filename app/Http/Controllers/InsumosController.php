<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
class InsumosController extends Controller
{
    public function index()
    {
      
  
        $insumos = Insumos::with('Articulo_inventariados.Catalogo_articulos')->get();
  
        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('insumos.index',compact('insumos'));
    }
}
