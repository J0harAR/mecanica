<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;

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



  public function destroy($id_maquinaria)
  {
      $maquinaria=Articulo_inventariado::find($id_maquinaria);
     //Actualizar Catalogo articulos
      $catalogo_articulo=Catalogo_articulo::find($maquinaria->id_articulo);
      if($catalogo_articulo->cantidad>0){
          $catalogo_articulo->cantidad-=1;
          $catalogo_articulo->save();

      }else{
          $catalogo_articulo->cantidad=0;
          $catalogo_articulo->save();
      }
      $maquinaria->delete();
      return redirect()->route('maquinaria.index');

     
  }
}
