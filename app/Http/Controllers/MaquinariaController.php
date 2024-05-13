<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Auditoria;
use App\Models\Insumos;
class MaquinariaController extends Controller
{
  public function index()
  {
    

      $maquinaria = Maquinaria::with(['Articulo_inventariados.Catalogo_articulos','insumos'])->get();
      $insumos=Insumos::all();
      //foreach ($herramientas as $herramienta) {
        //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
    //  }
      
      return view('maquinaria.index',compact('maquinaria','insumos'));
  }

  public function update(Request $request,$id_maquinaria)
  {
      //Request
      $estatus_maquinaria=$request->input('estatus');

      $auditoria_inventario=new Auditoria;

      $articulo_inventariado=Articulo_inventariado::find($id_maquinaria);      
    
        if($articulo_inventariado->estatus !== $estatus_maquinaria){
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);

            $articulo_inventariado->estatus=$estatus_maquinaria;
            $articulo_inventariado->save();

            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
        }

        $maquinaria=Maquinaria::find($id_maquinaria);


          $insumos=collect($request->input('insumos',[]))
                ->map(function($insumo){
                  return ['cantidad'=>$insumo];
                });

              
              $maquinaria->insumos()->sync($insumos);
        




      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido actualizada exitosamente.');
  }

  public function show($id_maquinaria){

    //$maquinaria=Articulo_inventariado::with(['Articulo_inventariados.Catalogo_articulos','insumos'])->where('id_inventario',$id_maquinaria);

    $maquinaria=Maquinaria::find($id_maquinaria);
    
    return view('maquinaria.show',compact('maquinaria'));
  }


  public function asignar_cantidad_insumos(Request $request,$id_maquinaria){
    $maquinaria=Maquinaria::find($id_maquinaria);

    $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
      });

    
    $maquinaria->insumos()->sync($insumos);
  }



  public function destroy($id_maquinaria)
  {
      $maquinaria=Articulo_inventariado::find($id_maquinaria);
      
      $auditoria=new Auditoria;
      $auditoria->event='deleted';  
      $auditoria->subject_type=Maquinaria::class;
      $auditoria->subject_id=$maquinaria->id_inventario;
      $auditoria->cause_id=auth()->id();
      $auditoria->old_data=json_encode($maquinaria);
      $auditoria->new_data=json_encode([]);
      $auditoria->save();



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
      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido eliminada exitosamente.');

     
  }
}
