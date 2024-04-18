<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
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

    public function update(Request $request,$id_insumo)
    {
        //Request
        $estatus_insumo=$request->input('estatus');
       
        $articulo_inventariado=Articulo_inventariado::find($id_insumo);      
        $articulo_inventariado->estatus=$estatus_insumo;
        $articulo_inventariado->save();
  
        return redirect()->route('insumos.index');
    }




    public function destroy($id_insumo)
    {
        $insumo=Articulo_inventariado::find($id_insumo);
       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($insumo->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }else{
            $catalogo_articulo->cantidad=0;
            $catalogo_articulo->save();
        }
        $insumo->delete();
        return redirect()->route('insumos.index');

       
    }
}
