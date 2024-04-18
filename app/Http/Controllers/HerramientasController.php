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

    public function update(Request $request,$id_herramientas)
    {
        //Request
        $condicion_herramienta=$request->input('condicion_herramienta');
        $estatus_herramienta=$request->input('estatus');
       

        $articulo_inventariado=Articulo_inventariado::find($id_herramientas);      
        $articulo_inventariado->estatus=$estatus_herramienta;
        $articulo_inventariado->save();


        $herramienta=Herramientas::find($id_herramientas);
        $herramienta->condicion=$condicion_herramienta;
        $herramienta->save();

        return redirect()->route('herramientas.index');
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
