<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
class HerramientasController extends Controller
{
    public function index()
    {
      

        $herramientas = Herramientas::with('Articulo_inventariados.Catalogo_articulos')->get();

        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('herramientas.index',compact('herramientas'));
    }
}
