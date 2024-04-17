<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
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


    public function destroy($id_herramientas)
    {
        $herramienta=Articulo_inventariado::find($id_herramientas);
       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($herramienta->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }else{
            $catalogo_articulo->cantidad=0;
            $catalogo_articulo->save();
        }
        $herramienta->delete();
        return redirect()->route('herramientas.index');

       
    }
}
